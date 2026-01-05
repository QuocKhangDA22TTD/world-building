@extends('layouts.app')

@section('title', 'Entities')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900">Entities - {{ $world->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('entities.create', ['world_id' => $world->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Create Entity
            </a>
            <a href="{{ route('worlds.show', $world) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Back to World
            </a>
        </div>
    </div>

    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-4">
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search by name..." value="{{ request('search') }}"
                class="px-3 py-2 border border-gray-300 rounded">
            
            <select name="type_id" class="px-3 py-2 border border-gray-300 rounded">
                <option value="">All Types</option>
                @foreach($types as $type)
                <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>

            <select name="tag_id" class="px-3 py-2 border border-gray-300 rounded">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Filter
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tags</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($entities as $entity)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $entity->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $entity->entityType->name }}</td>
                <td class="px-6 py-4">
                    @foreach($entity->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">{{ $tag->name }}</span>
                    @endforeach
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('entities.show', $entity) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <a href="{{ route('entities.edit', $entity) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form method="POST" action="{{ route('entities.destroy', $entity) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $entities->links() }}
</div>
@endsection
