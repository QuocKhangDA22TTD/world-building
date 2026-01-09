@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-theme-primary">Tags - {{ $world->name }}</h1>
        <a href="{{ route('worlds.show', $world) }}" class="btn-secondary">
            Back to World
        </a>
    </div>
</div>

<div class="glass-card rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold text-theme-primary mb-4">Create New Tag</h2>
    <form method="POST" action="{{ route('tags.store') }}" class="flex gap-3">
        @csrf
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        <input type="text" name="name" placeholder="Tag name..." required class="input-modern flex-1">
        <button type="submit" class="btn-primary">
            Create
        </button>
    </form>
</div>

<div class="glass-card rounded-lg overflow-hidden">
    <table class="table-modern min-w-full">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Entities Count</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($tags as $tag)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="tag tag-blue">{{ $tag->name }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-theme-secondary">{{ $tag->entities_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-theme-muted">No tags yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
