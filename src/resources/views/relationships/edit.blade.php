@extends('layouts.app')

@section('title', 'Edit Relationship')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-theme-primary mb-6">Edit Relationship</h1>

    <form method="POST" action="{{ route('relationships.update', $relationship) }}" class="glass-card p-6 rounded-lg">
        @csrf @method('PUT')
        
        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">From Entity</label>
            <select name="from_entity_id" required class="input-modern w-full">
                @foreach($entities as $entity)
                <option value="{{ $entity->id }}" {{ old('from_entity_id', $relationship->from_entity_id) == $entity->id ? 'selected' : '' }}>
                    {{ $entity->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Relation Type</label>
            <input type="text" name="relation_type" value="{{ old('relation_type', $relationship->relation_type) }}" required
                class="input-modern w-full">
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">To Entity</label>
            <select name="to_entity_id" required class="input-modern w-full">
                @foreach($entities as $entity)
                <option value="{{ $entity->id }}" {{ old('to_entity_id', $relationship->to_entity_id) == $entity->id ? 'selected' : '' }}>
                    {{ $entity->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4" class="input-modern w-full">{{ old('description', $relationship->description) }}</textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('relationships.index', ['world_id' => $relationship->world_id]) }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Update Relationship
            </button>
        </div>
    </form>
</div>
@endsection
