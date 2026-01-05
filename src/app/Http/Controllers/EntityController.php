<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\World;
use App\Models\EntityType;
use App\Models\Tag;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function index(Request $request)
    {
        $worldId = $request->world_id;
        $world = World::findOrFail($worldId);
        
        $query = Entity::where('world_id', $worldId)->with(['entityType', 'tags']);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Filter by type
        if ($request->filled('type_id')) {
            $query->where('entity_type_id', $request->type_id);
        }

        // Filter by tag
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }

        $entities = $query->paginate(20);
        $types = EntityType::where('world_id', $worldId)->get();
        $tags = Tag::where('world_id', $worldId)->get();

        return view('entities.index', compact('entities', 'world', 'types', 'tags'));
    }

    public function create(Request $request)
    {
        $worldId = $request->world_id;
        $world = World::findOrFail($worldId);
        $types = EntityType::where('world_id', $worldId)->get();
        $tags = Tag::where('world_id', $worldId)->get();

        return view('entities.create', compact('world', 'types', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'world_id' => 'required|exists:worlds,id',
            'entity_type_id' => 'required|exists:entity_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        $entity = Entity::create($validated);
        
        if ($request->filled('tags')) {
            $entity->tags()->sync($request->tags);
        }

        return redirect()->route('entities.show', $entity)->with('success', 'Entity created');
    }

    public function show(Entity $entity)
    {
        $entity->load(['entityType', 'tags', 'relationshipsFrom.toEntity', 'relationshipsTo.fromEntity']);
        return view('entities.show', compact('entity'));
    }

    public function edit(Entity $entity)
    {
        $types = EntityType::where('world_id', $entity->world_id)->get();
        $tags = Tag::where('world_id', $entity->world_id)->get();
        return view('entities.edit', compact('entity', 'types', 'tags'));
    }

    public function update(Request $request, Entity $entity)
    {
        $validated = $request->validate([
            'entity_type_id' => 'required|exists:entity_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        $entity->update($validated);
        
        if ($request->has('tags')) {
            $entity->tags()->sync($request->tags ?? []);
        }

        return redirect()->route('entities.show', $entity)->with('success', 'Entity updated');
    }

    public function destroy(Entity $entity)
    {
        $worldId = $entity->world_id;
        $entity->delete();
        return redirect()->route('entities.index', ['world_id' => $worldId])->with('success', 'Entity deleted');
    }
}
