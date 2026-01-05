@extends('layouts.app')

@section('title', 'Worlds')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">My Worlds</h1>
    <a href="{{ route('worlds.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Create World
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entities</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Types</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($worlds as $world)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $world->name }}</td>
                <td class="px-6 py-4">{{ Str::limit($world->description, 50) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $world->entities_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $world->entity_types_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('worlds.show', $world) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <a href="{{ route('worlds.edit', $world) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    <form method="POST" action="{{ route('worlds.destroy', $world) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
