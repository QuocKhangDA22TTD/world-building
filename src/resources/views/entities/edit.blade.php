@extends('layouts.app')

@section('title', 'Edit Entity')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Entity</h1>

    <form method="POST" action="{{ route('entities.update', $entity) }}" class="bg-white p-6 rounded-lg shadow">
        @csrf @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $entity->name) }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Entity Type</label>
            <select name="entity_type_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                @foreach($types as $type)
                <option value="{{ $type->id }}" {{ old('entity_type_id', $entity->entity_type_id) == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('description', $entity->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
            <div class="space-y-2">
                @foreach($tags as $tag)
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                        {{ $entity->tags->contains($tag->id) ? 'checked' : '' }} class="form-checkbox">
                    <span class="ml-2">{{ $tag->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('entities.show', $entity) }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Update Entity
            </button>
        </div>
    </form>
</div>
@endsection
