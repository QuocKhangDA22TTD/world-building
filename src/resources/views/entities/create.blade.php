@extends('layouts.app')

@section('title', 'Create Entity')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-theme-primary mb-6">Create Entity - {{ $world->name }}</h1>

    <form method="POST" action="{{ route('entities.store') }}" class="glass-card p-6 rounded-lg">
        @csrf
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        
        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="input-modern w-full">
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Entity Type</label>
            <select name="entity_type_id" required class="input-modern w-full">
                <option value="">Select Type</option>
                @foreach($types as $type)
                <option value="{{ $type->id }}" {{ old('entity_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="4" class="input-modern w-full">{{ old('description') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Tags</label>
            <div class="space-y-2">
                @foreach($tags as $tag)
                <label class="inline-flex items-center mr-4 text-theme-secondary">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-checkbox">
                    <span class="ml-2">{{ $tag->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('entities.index', ['world_id' => $world->id]) }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Create Entity
            </button>
        </div>
    </form>
</div>
@endsection
