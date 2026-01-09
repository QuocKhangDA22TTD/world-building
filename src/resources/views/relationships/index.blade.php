@extends('layouts.app')

@section('title', 'Relationships')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-theme-primary">Relationships - {{ $world->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('relationships.create', ['world_id' => $world->id]) }}" class="btn-primary">
                Create Relationship
            </a>
            <a href="{{ route('worlds.show', $world) }}" class="btn-secondary">
                Back to World
            </a>
        </div>
    </div>
</div>

<div class="glass-card rounded-lg overflow-hidden">
    <table class="table-modern min-w-full">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left">From Entity</th>
                <th class="px-6 py-3 text-left">Relation Type</th>
                <th class="px-6 py-3 text-left">To Entity</th>
                <th class="px-6 py-3 text-left">Description</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($relationships as $relationship)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-theme-primary">{{ $relationship->fromEntity->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="tag tag-purple">{{ $relationship->relation_type }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-theme-primary">{{ $relationship->toEntity->name }}</td>
                <td class="px-6 py-4 text-theme-secondary">{{ Str::limit($relationship->description, 50) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('relationships.edit', $relationship) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form method="POST" action="{{ route('relationships.destroy', $relationship) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-theme-muted">No relationships yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $relationships->links() }}
</div>
@endsection
