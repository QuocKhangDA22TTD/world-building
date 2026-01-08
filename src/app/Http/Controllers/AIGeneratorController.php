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
     * Chat với AI về world (API endpoint)
     */
    public function chat(Request $request, World $world)
    {
        // Kiểm tra quyền sở hữu
        if ($world->owner_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => __('You do not have permission to modify this world')
            ], 403);
        }
        
        $request->validate([
            'message' => 'required|string|min:1|max:2000',
            'chat_history' => 'array|max:20',
        ]);
        
        if (!$this->gemini->hasApiKeys()) {
            return response()->json([
                'success' => false,
                'error' => __('AI service is not configured')
            ], 503);
        }
        
        // Lấy dữ liệu world hiện tại
        $worldData = $this->getWorldData($world);
        $language = app()->getLocale();
        $chatHistory = $request->input('chat_history', []);
        
        // Gọi AI để phân tích tin nhắn
        $result = $this->gemini->chatAboutWorld($worldData, $request->message, $chatHistory, $language);
        
        if (!$result || !$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? __('Failed to process message')
            ], 500);
        }
        
        $data = $result['data'];
        
        // Nếu là yêu cầu chỉnh sửa
        if (($data['intent'] ?? '') === 'modification' && !empty($data['modification_request'])) {
            // Gọi AI để tạo changes
            $modifyResult = $this->gemini->modifyWorld($worldData, $data['modification_request'], $language);
            
            if ($modifyResult && $modifyResult['success']) {
                return response()->json([
                    'success' => true,
                    'intent' => 'modification',
                    'response' => $data['response'],
                    'changes' => $modifyResult['data'],
                    'requires_confirmation' => true
                ]);
            }
        }
        
        // Trả về câu trả lời thông thường
        return response()->json([
            'success' => true,
            'intent' => $data['intent'] ?? 'question',
            'response' => $data['response'] ?? __('I could not understand your request.')
        ]);
    }
    
    /**
     * Áp dụng các thay đổi từ AI
     */
    public function applyChanges(Request $request, World $world)
    {
        // Kiểm tra quyền sở hữu
        if ($world->owner_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'error' => __('You do not have permission to modify this world')
            ], 403);
        }
        
        $request->validate([
            'changes' => 'required|array',
        ]);
        
        try {
            $changes = $request->input('changes');
            $appliedChanges = $this->applyWorldChanges($world, $changes);
            
            // Reload world data
            $world->refresh();
            $world->load(['entityTypes', 'entities.entityType', 'entities.tags', 'relationships.fromEntity', 'relationships.toEntity', 'tags']);
            
            return response()->json([
                'success' => true,
                'message' => __('Changes applied successfully!'),
                'applied' => $appliedChanges,
                'world' => $this->getWorldData($world)
            ]);
            
        } catch (\Exception $e) {
            Log::error('AI Apply Changes Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => __('Failed to apply changes: ') . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy dữ liệu world dạng array
     */
    protected function getWorldData(World $world): array
    {
        $world->load(['entityTypes', 'entities.entityType', 'entities.tags', 'relationships.fromEntity', 'relationships.toEntity', 'tags']);
        
        return [
            'world' => [
                'id' => $world->id,
                'name' => $world->name,
                'description' => $world->description,
            ],
            'entity_types' => $world->entityTypes->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->toArray(),
            'entities' => $world->entities->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'type' => $e->entityType->name ?? null,
                'description' => $e->description,
            ])->toArray(),
            'relationships' => $world->relationships->map(fn($r) => [
                'id' => $r->id,
                'from' => $r->fromEntity->name ?? null,
                'to' => $r->toEntity->name ?? null,
                'type' => $r->relation_type,
                'description' => $r->description,
            ])->toArray(),
            'tags' => $world->tags->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->toArray(),
            'entity_tags' => $world->entities->filter(fn($e) => $e->tags->isNotEmpty())->map(fn($e) => [
                'entity' => $e->name,
                'tags' => $e->tags->pluck('name')->toArray(),
            ])->values()->toArray(),
        ];
    }
    
    /**
     * Áp dụng các thay đổi vào world
     */
    protected function applyWorldChanges(World $world, array $changes): array
    {
        $applied = [];
        
        return DB::transaction(function () use ($world, $changes, &$applied) {
            $changesData = $changes['changes'] ?? $changes;
            
            // 1. Update World info
            if (!empty($changesData['world']['update'])) {
                $updateData = $changesData['world']['update'];
                if (!empty($updateData['name'])) {
                    $world->name = $updateData['name'];
                }
                if (!empty($updateData['description'])) {
                    $world->description = $updateData['description'];
                }
                $world->save();
                $applied['world_updated'] = true;
            }
            
            // 2. Entity Types
            if (!empty($changesData['entity_types'])) {
                // Add new types
                foreach ($changesData['entity_types']['add'] ?? [] as $typeData) {
                    EntityType::create([
                        'world_id' => $world->id,
                        'name' => $typeData['name'],
                    ]);
                    $applied['entity_types_added'][] = $typeData['name'];
                }
                
                // Remove types
                foreach ($changesData['entity_types']['remove'] ?? [] as $typeName) {
                    EntityType::where('world_id', $world->id)
                        ->where('name', $typeName)
                        ->delete();
                    $applied['entity_types_removed'][] = $typeName;
                }
                
                // Update types
                foreach ($changesData['entity_types']['update'] ?? [] as $typeUpdate) {
                    EntityType::where('world_id', $world->id)
                        ->where('name', $typeUpdate['old_name'])
                        ->update(['name' => $typeUpdate['new_name']]);
                    $applied['entity_types_updated'][] = $typeUpdate;
                }
            }
            
            // 3. Tags (process before entities for entity_tags)
            if (!empty($changesData['tags'])) {
                foreach ($changesData['tags']['add'] ?? [] as $tagData) {
                    Tag::create([
                        'world_id' => $world->id,
                        'name' => $tagData['name'],
                    ]);
                    $applied['tags_added'][] = $tagData['name'];
                }
                
                foreach ($changesData['tags']['remove'] ?? [] as $tagName) {
                    Tag::where('world_id', $world->id)
                        ->where('name', $tagName)
                        ->delete();
                    $applied['tags_removed'][] = $tagName;
                }
            }
            
            // 4. Entities
            if (!empty($changesData['entities'])) {
                // Add new entities
                foreach ($changesData['entities']['add'] ?? [] as $entityData) {
                    $typeId = null;
                    if (!empty($entityData['type'])) {
                        $type = EntityType::where('world_id', $world->id)
                            ->where('name', $entityData['type'])
                            ->first();
                        $typeId = $type?->id;
                    }
                    
                    Entity::create([
                        'world_id' => $world->id,
                        'entity_type_id' => $typeId,
                        'name' => $entityData['name'],
                        'description' => $entityData['description'] ?? '',
                    ]);
                    $applied['entities_added'][] = $entityData['name'];
                }
                
                // Remove entities
                foreach ($changesData['entities']['remove'] ?? [] as $entityName) {
                    Entity::where('world_id', $world->id)
                        ->where('name', $entityName)
                        ->delete();
                    $applied['entities_removed'][] = $entityName;
                }
                
                // Update entities
                foreach ($changesData['entities']['update'] ?? [] as $entityUpdate) {
                    $entity = Entity::where('world_id', $world->id)
                        ->where('name', $entityUpdate['name'])
                        ->first();
                    
                    if ($entity && !empty($entityUpdate['changes'])) {
                        $updateData = [];
                        if (isset($entityUpdate['changes']['description'])) {
                            $updateData['description'] = $entityUpdate['changes']['description'];
                        }
                        if (isset($entityUpdate['changes']['name'])) {
                            $updateData['name'] = $entityUpdate['changes']['name'];
                        }
                        if (isset($entityUpdate['changes']['type'])) {
                            $type = EntityType::where('world_id', $world->id)
                                ->where('name', $entityUpdate['changes']['type'])
                                ->first();
                            if ($type) {
                                $updateData['entity_type_id'] = $type->id;
                            }
                        }
                        if (!empty($updateData)) {
                            $entity->update($updateData);
                            $applied['entities_updated'][] = $entityUpdate['name'];
                        }
                    }
                }
            }
            
            // 5. Relationships
            if (!empty($changesData['relationships'])) {
                // Add new relationships
                foreach ($changesData['relationships']['add'] ?? [] as $relData) {
                    $fromEntity = Entity::where('world_id', $world->id)
                        ->where('name', $relData['from'])
                        ->first();
                    $toEntity = Entity::where('world_id', $world->id)
                        ->where('name', $relData['to'])
                        ->first();
                    
                    if ($fromEntity && $toEntity) {
                        Relationship::create([
                            'world_id' => $world->id,
                            'from_entity_id' => $fromEntity->id,
                            'to_entity_id' => $toEntity->id,
                            'relation_type' => $relData['type'] ?? 'related',
                            'description' => $relData['description'] ?? '',
                        ]);
                        $applied['relationships_added'][] = "{$relData['from']} -> {$relData['to']}";
                    }
                }
                
                // Remove relationships
                foreach ($changesData['relationships']['remove'] ?? [] as $relData) {
                    $fromEntity = Entity::where('world_id', $world->id)
                        ->where('name', $relData['from'])
                        ->first();
                    $toEntity = Entity::where('world_id', $world->id)
                        ->where('name', $relData['to'])
                        ->first();
                    
                    if ($fromEntity && $toEntity) {
                        Relationship::where('world_id', $world->id)
                            ->where('from_entity_id', $fromEntity->id)
                            ->where('to_entity_id', $toEntity->id)
                            ->delete();
                        $applied['relationships_removed'][] = "{$relData['from']} -> {$relData['to']}";
                    }
                }
            }
            
            // 6. Entity Tags
            if (!empty($changesData['entity_tags'])) {
                // Add tags to entities
                foreach ($changesData['entity_tags']['add'] ?? [] as $etData) {
                    $entity = Entity::where('world_id', $world->id)
                        ->where('name', $etData['entity'])
                        ->first();
                    
                    if ($entity) {
                        $tagIds = Tag::where('world_id', $world->id)
                            ->whereIn('name', $etData['tags'] ?? [])
                            ->pluck('id')
                            ->toArray();
                        
                        if (!empty($tagIds)) {
                            $entity->tags()->syncWithoutDetaching($tagIds);
                            $applied['entity_tags_added'][] = $etData['entity'];
                        }
                    }
                }
                
                // Remove tags from entities
                foreach ($changesData['entity_tags']['remove'] ?? [] as $etData) {
                    $entity = Entity::where('world_id', $world->id)
                        ->where('name', $etData['entity'])
                        ->first();
                    
                    if ($entity) {
                        $tagIds = Tag::where('world_id', $world->id)
                            ->whereIn('name', $etData['tags'] ?? [])
                            ->pluck('id')
                            ->toArray();
                        
                        if (!empty($tagIds)) {
                            $entity->tags()->detach($tagIds);
                            $applied['entity_tags_removed'][] = $etData['entity'];
                        }
                    }
                }
            }
            
            return $applied;
        });
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
