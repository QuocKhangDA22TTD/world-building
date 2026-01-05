@extends('layouts.app')

@section('title', 'Create Relationship')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create Relationship - {{ $world->name }}</h1>

    <form method="POST" action="{{ route('relationships.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">From Entity</label>
            <select name="from_entity_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="">Select Entity</option>
                @foreach($entities as $entity)
                <option value="{{ $entity->id }}" {{ old('from_entity_id') == $entity->id ? 'selected' : '' }}>
                    {{ $entity->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Relation Type</label>
            <input type="text" name="relation_type" value="{{ old('relation_type') }}" placeholder="e.g., friend of, parent of, works for..." required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">To Entity</label>
            <select name="to_entity_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <option value="">Select Entity</option>
                @foreach($entities as $entity)
                <option value="{{ $entity->id }}" {{ old('to_entity_id') == $entity->id ? 'selected' : '' }}>
                    {{ $entity->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('relationships.index', ['world_id' => $world->id]) }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Create Relationship
            </button>
        </div>
    </form>
</div>
@endsection
