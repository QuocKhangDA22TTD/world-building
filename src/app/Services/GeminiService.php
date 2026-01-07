<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected array $apiKeys = [];
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
    
    public function __construct()
    {
        // Load API keys từ .env (hỗ trợ 2 cách: GEMINI_API_KEYS hoặc GEMINI_API_KEY_1, GEMINI_API_KEY_2, ...)
        $keys = config('services.gemini.api_keys', '');
        
        if (!empty($keys)) {
            // Cách 1: Nhiều keys trong 1 biến, phân cách bằng dấu phẩy
            $this->apiKeys = array_filter(explode(',', $keys));
        } else {
            // Cách 2: Mỗi key 1 biến riêng (GEMINI_API_KEY_1, GEMINI_API_KEY_2, ...)
            $this->apiKeys = [];
            for ($i = 1; $i <= 10; $i++) {
                $key = env("GEMINI_API_KEY_{$i}");
                if (!empty($key)) {
                    $this->apiKeys[] = $key;
                }
            }
        }
    }
    
    /**
     * Lấy API key tiếp theo theo cơ chế xoay vòng
     */
    protected function getNextApiKey(): ?string
    {
        if (empty($this->apiKeys)) {
            return null;
        }
        
        // Lấy index hiện tại từ cache
        $currentIndex = Cache::get('gemini_api_key_index', 0);
        
        // Tìm key còn hoạt động
        $attempts = 0;
        $totalKeys = count($this->apiKeys);
        
        while ($attempts < $totalKeys) {
            $keyIndex = ($currentIndex + $attempts) % $totalKeys;
            $key = trim($this->apiKeys[$keyIndex]);
            
            // Kiểm tra key có bị block không
            $blockedUntil = Cache::get("gemini_key_blocked_{$keyIndex}");
            
            if (!$blockedUntil || now()->gt($blockedUntil)) {
                // Cập nhật index cho lần gọi tiếp theo
                Cache::put('gemini_api_key_index', ($keyIndex + 1) % $totalKeys, 3600);
                return $key;
            }
            
            $attempts++;
        }
        
        return null;
    }
    
    /**
     * Đánh dấu key bị rate limit
     */
    protected function markKeyAsBlocked(int $keyIndex, int $minutes = 1): void
    {
        Cache::put("gemini_key_blocked_{$keyIndex}", now()->addMinutes($minutes), $minutes * 60);
    }
    
    /**
     * Gọi Gemini API với retry và xoay vòng key
     */
    public function generate(string $prompt, int $maxRetries = 3): ?array
    {
        $attempts = 0;
        
        while ($attempts < $maxRetries) {
            $apiKey = $this->getNextApiKey();
            
            if (!$apiKey) {
                Log::error('Gemini: Không có API key khả dụng');
                return null;
            }
            
            try {
                $response = Http::timeout(60)->post("{$this->baseUrl}?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 8192,
                    ]
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    return [
                        'success' => true,
                        'text' => $text,
                        'data' => $data
                    ];
                }
                
                // Xử lý lỗi rate limit (429)
                if ($response->status() === 429) {
                    $keyIndex = array_search($apiKey, $this->apiKeys);
                    $this->markKeyAsBlocked($keyIndex, 1);
                    Log::warning("Gemini: API key {$keyIndex} bị rate limit, chuyển sang key khác");
                    $attempts++;
                    continue;
                }
                
                // Lỗi khác
                Log::error('Gemini API Error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Unknown error'
                ];
                
            } catch (\Exception $e) {
                Log::error('Gemini Exception: ' . $e->getMessage());
                $attempts++;
            }
        }
        
        return [
            'success' => false,
            'error' => 'Đã hết số lần thử, vui lòng thử lại sau'
        ];
    }
    
    /**
     * Tạo World từ mô tả
     */
    public function generateWorld(string $description, string $language = 'vi'): ?array
    {
        $langInstruction = $language === 'vi' 
            ? 'Trả lời bằng tiếng Việt.' 
            : 'Respond in English.';
            
        $prompt = <<<PROMPT
{$langInstruction}
Bạn là một AI chuyên tạo thế giới (world building) cho game, truyện, phim.

Dựa trên mô tả sau, hãy tạo một thế giới hoàn chỉnh với cấu trúc JSON:

MÔ TẢ: {$description}

Trả về JSON với cấu trúc CHÍNH XÁC như sau (không thêm markdown, chỉ JSON thuần):
{
    "world": {
        "name": "Tên thế giới",
        "description": "Mô tả chi tiết về thế giới (2-3 đoạn văn)"
    },
    "entity_types": [
        {"name": "Loại 1"},
        {"name": "Loại 2"}
    ],
    "entities": [
        {
            "name": "Tên thực thể",
            "type": "Loại thực thể (phải khớp với entity_types)",
            "description": "Mô tả chi tiết"
        }
    ],
    "relationships": [
        {
            "from": "Tên thực thể 1",
            "to": "Tên thực thể 2", 
            "type": "Loại quan hệ (ví dụ: bạn bè, kẻ thù, gia đình, đồng minh)",
            "description": "Mô tả quan hệ"
        }
    ],
    "tags": [
        {"name": "Tag 1"},
        {"name": "Tag 2"}
    ],
    "entity_tags": [
        {
            "entity": "Tên thực thể",
            "tags": ["Tag 1", "Tag 2"]
        }
    ]
}

YÊU CẦU:
- Tạo ít nhất 3-5 entity_types phù hợp với thế giới
- Tạo ít nhất 5-10 entities đa dạng
- Tạo ít nhất 5-10 relationships giữa các entities
- Tạo ít nhất 5-10 tags để phân loại
- Gán tags cho các entities phù hợp
- Mô tả chi tiết, sáng tạo và hấp dẫn
- CHỈ trả về JSON, không có text khác
PROMPT;

        $result = $this->generate($prompt);
        
        if (!$result || !$result['success']) {
            return $result;
        }
        
        // Parse JSON từ response
        $text = $result['text'];
        
        // Loại bỏ markdown code block nếu có
        $text = preg_replace('/```json\s*/', '', $text);
        $text = preg_replace('/```\s*/', '', $text);
        $text = trim($text);
        
        $parsed = json_decode($text, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Gemini: Không thể parse JSON - ' . json_last_error_msg());
            Log::error('Raw text: ' . $text);
            return [
                'success' => false,
                'error' => 'Không thể xử lý phản hồi từ AI'
            ];
        }
        
        return [
            'success' => true,
            'data' => $parsed
        ];
    }
    
    /**
     * Kiểm tra API keys có sẵn không
     */
    public function hasApiKeys(): bool
    {
        return !empty($this->apiKeys);
    }
    
    /**
     * Lấy số lượng API keys
     */
    public function getApiKeyCount(): int
    {
        return count($this->apiKeys);
    }
}
