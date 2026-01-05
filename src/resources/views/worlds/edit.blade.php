@extends('layouts.app')

@section('title', 'Edit World')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit World</h1>

    <form method="POST" action="{{ route('worlds.update', $world) }}" class="bg-white p-6 rounded-lg shadow">
        @csrf @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $world->name) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('description', $world->description) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('worlds.show', $world) }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update World
            </button>
        </div>
    </form>
</div>
@endsection
