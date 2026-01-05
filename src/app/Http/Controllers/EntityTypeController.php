<?php

namespace App\Http\Controllers;

use App\Models\EntityType;
use App\Models\World;
use Illuminate\Http\Request;

class EntityTypeController extends Controller
{
    public function index(Request $request)
    {
        $worldId = $request->world_id;
        $types = EntityType::where('world_id', $worldId)->withCount('entities')->get();
        $world = World::findOrFail($worldId);
        
        return view('entity-types.index', compact('types', 'world'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'world_id' => 'required|exists:worlds,id',
            'name' => 'required|string|max:100',
        ]);

        EntityType::create($validated);
        return back()->with('success', 'Entity type created');
    }

    public function update(Request $request, EntityType $entityType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $entityType->update($validated);
        return back()->with('success', 'Entity type updated');
    }

    public function destroy(EntityType $entityType)
    {
        $entityType->delete();
        return back()->with('success', 'Entity type deleted');
    }
}
