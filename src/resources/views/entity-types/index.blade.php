@extends('layouts.app')

@section('title', 'Entity Types')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900">Entity Types - {{ $world->name }}</h1>
        <a href="{{ route('worlds.show', $world) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Back to World
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Create New Entity Type</h2>
    <form method="POST" action="{{ route('entity-types.store') }}" class="flex gap-3">
        @csrf
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        <input type="text" name="name" placeholder="Type name..." required
            class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Create
        </button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entities Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($types as $type)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $type->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $type->entities_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <form method="POST" action="{{ route('entity-types.destroy', $type) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No entity types yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
