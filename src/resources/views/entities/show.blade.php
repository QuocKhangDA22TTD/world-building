@extends('layouts.app')

@section('title', $entity->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-theme-primary">{{ $entity->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('entities.edit', $entity) }}" class="btn-success">Edit</a>
            <a href="{{ route('entities.index', ['world_id' => $entity->world_id]) }}" class="btn-secondary">Back</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="glass-card rounded-lg p-6">
            <h2 class="text-xl font-semibold text-theme-primary mb-4">Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold text-theme-secondary">Type:</span>
                    <span class="ml-2 text-theme-secondary">{{ $entity->entityType->name }}</span>
                </div>
                <div>
                    <span class="font-semibold text-theme-secondary">Description:</span>
                    <p class="mt-2 text-theme-muted">{{ $entity->description ?: 'No description' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-theme-secondary">Tags:</span>
                    <div class="mt-2">
                        @forelse($entity->tags as $tag)
                        <span class="tag tag-blue mr-2 mb-2">{{ $tag->name }}</span>
                        @empty
                        <span class="text-theme-muted">No tags</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-lg p-6">
            <h2 class="text-xl font-semibold text-theme-primary mb-4">Relationships</h2>
            @if($entity->relationshipsFrom->isEmpty() && $entity->relationshipsTo->isEmpty())
            <p class="text-theme-muted">No relationships</p>
            @else
            <div class="space-y-3">
                @foreach($entity->relationshipsFrom as $rel)
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="font-medium text-theme-primary">{{ $entity->name }} <span class="text-blue-600">{{ $rel->relation_type }}</span> {{ $rel->toEntity->name }}</div>
                    @if($rel->description)
                    <p class="text-sm text-theme-muted mt-1">{{ $rel->description }}</p>
                    @endif
                </div>
                @endforeach

                @foreach($entity->relationshipsTo as $rel)
                <div class="border-l-4 border-green-500 pl-4">
                    <div class="font-medium text-theme-primary">{{ $rel->fromEntity->name }} <span class="text-green-600">{{ $rel->relation_type }}</span> {{ $entity->name }}</div>
                    @if($rel->description)
                    <p class="text-sm text-theme-muted mt-1">{{ $rel->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div>
        <div class="glass-card rounded-lg p-6">
            <h2 class="text-xl font-semibold text-theme-primary mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('relationships.create', ['world_id' => $entity->world_id, 'from_entity_id' => $entity->id]) }}" 
                   class="block w-full text-center btn-primary">
                    Add Relationship
                </a>
                <a href="{{ route('entities.edit', $entity) }}" 
                   class="block w-full text-center btn-success">
                    Edit Entity
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
