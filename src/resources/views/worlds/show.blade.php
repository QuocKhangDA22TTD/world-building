@extends('layouts.app')

@section('title', $world->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900">{{ $world->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('worlds.edit', $world) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Edit</a>
            <a href="{{ route('worlds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </div>
    <p class="text-gray-600">{{ $world->description }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('entities.index', ['world_id' => $world->id]) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-700">Entities</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $world->entities->count() }}</p>
    </a>
    
    <a href="{{ route('entity-types.index', ['world_id' => $world->id]) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-700">Entity Types</h3>
        <p class="text-3xl font-bold text-green-600">{{ $world->entityTypes->count() }}</p>
    </a>
    
    <a href="{{ route('relationships.index', ['world_id' => $world->id]) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-700">Relationships</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $world->relationships->count() }}</p>
    </a>
    
    <a href="{{ route('tags.index', ['world_id' => $world->id]) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-700">Tags</h3>
        <p class="text-3xl font-bold text-orange-600">{{ $world->tags->count() }}</p>
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Recent Entities</h2>
    @if($world->entities->isEmpty())
    <p class="text-gray-500">No entities yet.</p>
    @else
    <div class="space-y-2">
        @foreach($world->entities->take(10) as $entity)
        <div class="flex justify-between items-center border-b pb-2">
            <div>
                <span class="font-medium">{{ $entity->name }}</span>
                <span class="text-sm text-gray-500 ml-2">({{ $entity->entityType->name }})</span>
            </div>
            <a href="{{ route('entities.show', $entity) }}" class="text-blue-500 hover:text-blue-700">View</a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
