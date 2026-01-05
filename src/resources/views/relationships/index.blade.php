@extends('layouts.app')

@section('title', 'Relationships')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900">Relationships - {{ $world->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('relationships.create', ['world_id' => $world->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Create Relationship
            </a>
            <a href="{{ route('worlds.show', $world) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to World
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Entity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Relation Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Entity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($relationships as $relationship)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $relationship->fromEntity->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">{{ $relationship->relation_type }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $relationship->toEntity->name }}</td>
                <td class="px-6 py-4">{{ Str::limit($relationship->description, 50) }}</td>
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
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No relationships yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $relationships->links() }}
</div>
@endsection
