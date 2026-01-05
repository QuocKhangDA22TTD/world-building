<?php

namespace App\Http\Controllers;

use App\Models\Relationship;
use App\Models\World;
use App\Models\Entity;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    public function index(Request $request)
    {
        $worldId = $request->world_id;
        $world = World::findOrFail($worldId);
        $relationships = Relationship::where('world_id', $worldId)
            ->with(['fromEntity', 'toEntity'])
            ->paginate(20);

        return view('relationships.index', compact('relationships', 'world'));
    }

    public function create(Request $request)
    {
        $worldId = $request->world_id;
        $world = World::findOrFail($worldId);
        $entities = Entity::where('world_id', $worldId)->get();

        return view('relationships.create', compact('world', 'entities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'world_id' => 'required|exists:worlds,id',
            'from_entity_id' => 'required|exists:entities,id',
            'to_entity_id' => 'required|exists:entities,id|different:from_entity_id',
            'relation_type' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Relationship::create($validated);
        return redirect()->route('relationships.index', ['world_id' => $validated['world_id']])
            ->with('success', 'Relationship created');
    }

    public function edit(Relationship $relationship)
    {
        $entities = Entity::where('world_id', $relationship->world_id)->get();
        return view('relationships.edit', compact('relationship', 'entities'));
    }

    public function update(Request $request, Relationship $relationship)
    {
        $validated = $request->validate([
            'from_entity_id' => 'required|exists:entities,id',
            'to_entity_id' => 'required|exists:entities,id|different:from_entity_id',
            'relation_type' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $relationship->update($validated);
        return redirect()->route('relationships.index', ['world_id' => $relationship->world_id])
            ->with('success', 'Relationship updated');
    }

    public function destroy(Relationship $relationship)
    {
        $worldId = $relationship->world_id;
        $relationship->delete();
        return redirect()->route('relationships.index', ['world_id' => $worldId])
            ->with('success', 'Relationship deleted');
    }
}
