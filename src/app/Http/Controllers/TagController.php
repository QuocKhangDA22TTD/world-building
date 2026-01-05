<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\World;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $worldId = $request->world_id;
        $world = World::findOrFail($worldId);
        $tags = Tag::where('world_id', $worldId)->withCount('entities')->get();

        return view('tags.index', compact('tags', 'world'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'world_id' => 'required|exists:worlds,id',
            'name' => 'required|string|max:100',
        ]);

        Tag::create($validated);
        return back()->with('success', 'Tag created');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $tag->update($validated);
        return back()->with('success', 'Tag updated');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag deleted');
    }
}
