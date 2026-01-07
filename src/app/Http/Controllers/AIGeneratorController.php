<?php

namespace App\Http\Controllers;

use App\Models\World;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Relationship;
use App\Models\Tag;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AIGeneratorController extends Controller
{
    protected GeminiService $gemini;
    
    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }
    
    /**
     * Hiển thị form tạo world bằng AI
     */
    public function create()
    {
        $hasApiKeys = $this->gemini->hasApiKeys();
        return view('ai-generator.create', compact('hasApiKeys'));
    }
    
    /**
     * Tạo world từ mô tả bằng AI
     */
    public function generate(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:10|max:2000',
        ], [
            'description.required' => __('Please enter a description'),
            'description.min' => __('Description must be at least 10 characters'),
            'description.max' => __('Description must not exceed 2000 characters'),
        ]);
        
        if (!$this->gemini->hasApiKeys()) {
            return back()->with('error', __('AI service is not configured. Please contact administrator.'));
        }
        
        $language = app()->getLocale();
        $result = $this->gemini->generateWorld($request->description, $language);
        
        if (!$result || !$result['success']) {
            $error = $result['error'] ?? __('Failed to generate world. Please try again.');
            return back()->with('error', $error)->withInput();
        }
        
        // Lưu vào database
        try {
            $world = $this->saveGeneratedWorld($result['data'], auth()->id());
            
            return redirect()
                ->route('worlds.show', $world)
                ->with('success', __('World created successfully by AI!'));
                
        } catch (\Exception $e) {
            Log::error('AI Generator Error: ' . $e->getMessage());
            return back()->with('error', __('Failed to save world. Please try again.'))->withInput();
        }
    }
    
    /**
     * API endpoint để generate (cho AJAX)
     */
    public function generateApi(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:10|max:2000',
        ]);
        
        if (!$this->gemini->hasApiKeys()) {
            return response()->json([
                'success' => false,
                'error' => __('AI service is not configured')
            ], 503);
        }
        
        $language = app()->getLocale();
        $result = $this->gemini->generateWorld($request->description, $language);
        
        if (!$result || !$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? __('Failed to generate')
            ], 500);
        }
        
        try {
            $world = $this->saveGeneratedWorld($result['data'], auth()->id());
            
            return response()->json([
                'success' => true,
                'world' => $world,
                'redirect' => route('worlds.show', $world)
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Generator API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => __('Failed to save world')
            ], 500);
        }
    }
    
    /**
     * Lưu world được generate vào database
     */
    protected function saveGeneratedWorld(array $data, int $userId): World
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Tạo World
            $world = World::create([
                'owner_id' => $userId,
                'name' => $data['world']['name'],
                'description' => $data['world']['description'],
            ]);
            
            // 2. Tạo Entity Types
            $entityTypes = [];
            foreach ($data['entity_types'] ?? [] as $typeData) {
                $type = EntityType::create([
                    'world_id' => $world->id,
                    'name' => $typeData['name'],
                ]);
                $entityTypes[$typeData['name']] = $type;
            }
            
            // 3. Tạo Tags
            $tags = [];
            foreach ($data['tags'] ?? [] as $tagData) {
                $tag = Tag::create([
                    'world_id' => $world->id,
                    'name' => $tagData['name'],
                ]);
                $tags[$tagData['name']] = $tag;
            }
            
            // 4. Tạo Entities
            $entities = [];
            foreach ($data['entities'] ?? [] as $entityData) {
                $typeId = null;
                if (isset($entityData['type']) && isset($entityTypes[$entityData['type']])) {
                    $typeId = $entityTypes[$entityData['type']]->id;
                } elseif (!empty($entityTypes)) {
                    // Fallback: dùng type đầu tiên
                    $typeId = reset($entityTypes)->id;
                }
                
                $entity = Entity::create([
                    'world_id' => $world->id,
                    'entity_type_id' => $typeId,
                    'name' => $entityData['name'],
                    'description' => $entityData['description'] ?? '',
                ]);
                $entities[$entityData['name']] = $entity;
            }
            
            // 5. Gán Tags cho Entities
            foreach ($data['entity_tags'] ?? [] as $entityTagData) {
                $entityName = $entityTagData['entity'];
                if (isset($entities[$entityName])) {
                    $entity = $entities[$entityName];
                    $tagIds = [];
                    foreach ($entityTagData['tags'] ?? [] as $tagName) {
                        if (isset($tags[$tagName])) {
                            $tagIds[] = $tags[$tagName]->id;
                        }
                    }
                    if (!empty($tagIds)) {
                        $entity->tags()->attach($tagIds);
                    }
                }
            }
            
            // 6. Tạo Relationships
            foreach ($data['relationships'] ?? [] as $relData) {
                $fromEntity = $entities[$relData['from']] ?? null;
                $toEntity = $entities[$relData['to']] ?? null;
                
                if ($fromEntity && $toEntity) {
                    Relationship::create([
                        'world_id' => $world->id,
                        'from_entity_id' => $fromEntity->id,
                        'to_entity_id' => $toEntity->id,
                        'relation_type' => $relData['type'] ?? 'related',
                        'description' => $relData['description'] ?? '',
                    ]);
                }
            }
            
            return $world;
        });
    }
}
