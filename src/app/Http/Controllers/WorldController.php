<?php

namespace App\Http\Controllers;

use App\Models\World;
use Illuminate\Http\Request;

class WorldController extends Controller
{
    public function index()
    {
        $worlds = auth()->user()->worlds()->withCount(['entities', 'entityTypes'])->get();
        return view('worlds.index', compact('worlds'));
    }

    public function create()
    {
        return view('worlds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = auth()->id();
        World::create($validated);

        return redirect()->route('worlds.index')->with('success', 'World created');
    }

    public function show(World $world)
    {
        $this->authorize('view', $world);
        
        $world->load(['entityTypes', 'entities.entityType', 'tags']);
        return view('worlds.show', compact('world'));
    }

    public function edit(World $world)
    {
        $this->authorize('update', $world);
        return view('worlds.edit', compact('world'));
    }

    public function update(Request $request, World $world)
    {
        $this->authorize('update', $world);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $world->update($validated);
        return redirect()->route('worlds.show', $world)->with('success', 'World updated');
    }

    public function destroy(World $world)
    {
        $this->authorize('delete', $world);
        $world->delete();
        return redirect()->route('worlds.index')->with('success', 'World deleted');
    }
}
