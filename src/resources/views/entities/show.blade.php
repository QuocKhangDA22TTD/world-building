@extends('layouts.app')

@section('title', $entity->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900">{{ $entity->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('entities.edit', $entity) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Edit</a>
            <a href="{{ route('entities.index', ['world_id' => $entity->world_id]) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Details</h2>
            <div class="space-y-3">
                <div>
                    <span class="font-semibold text-gray-700">Type:</span>
                    <span class="ml-2">{{ $entity->entityType->name }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Description:</span>
                    <p class="mt-2 text-gray-600">{{ $entity->description ?: 'No description' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-700">Tags:</span>
                    <div class="mt-2">
                        @forelse($entity->tags as $tag)
                        <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded mr-2 mb-2">{{ $tag->name }}</span>
                        @empty
                        <span class="text-gray-500">No tags</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Relationships</h2>
            @if($entity->relationshipsFrom->isEmpty() && $entity->relationshipsTo->isEmpty())
            <p class="text-gray-500">No relationships</p>
            @else
            <div class="space-y-3">
                @foreach($entity->relationshipsFrom as $rel)
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="font-medium">{{ $entity->name }} <span class="text-blue-600">{{ $rel->relation_type }}</span> {{ $rel->toEntity->name }}</div>
                    @if($rel->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $rel->description }}</p>
                    @endif
                </div>
                @endforeach

                @foreach($entity->relationshipsTo as $rel)
                <div class="border-l-4 border-green-500 pl-4">
                    <div class="font-medium">{{ $rel->fromEntity->name }} <span class="text-green-600">{{ $rel->relation_type }}</span> {{ $entity->name }}</div>
                    @if($rel->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $rel->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="{{ route('relationships.create', ['world_id' => $entity->world_id, 'from_entity_id' => $entity->id]) }}" 
                   class="block w-full text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Add Relationship
                </a>
                <a href="{{ route('entities.edit', $entity) }}" 
                   class="block w-full text-center bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Edit Entity
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
