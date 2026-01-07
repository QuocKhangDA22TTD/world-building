@extends('layouts.app')

@section('title', $world->name)

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in-up">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold shadow-xl">
                {{ strtoupper(substr($world->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $world->name }}</h1>
                <p class="text-gray-400">{{ __('Created') }} {{ $world->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('worlds.edit', $world) }}" class="btn-success flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>{{ __('Edit') }}</span>
            </a>
            <a href="{{ route('worlds.index') }}" class="btn-secondary flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>
    @if($world->description)
    <div class="glass-card rounded-xl p-4 mt-4">
        <p class="text-gray-600">{{ $world->description }}</p>
    </div>
    @endif
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Entities -->
    <a href="{{ route('entities.index', ['world_id' => $world->id]) }}" class="stat-card glass-card card-hover animate-fade-in-up stagger-1" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">{{ __('Entities') }}</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-blue-500 to-cyan-500 bg-clip-text text-transparent">
                    {{ $world->entities->count() }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
    </a>
    
    <!-- Entity Types -->
    <a href="{{ route('entity-types.index', ['world_id' => $world->id]) }}" class="stat-card glass-card card-hover animate-fade-in-up stagger-2" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">{{ __('Entity Types') }}</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-green-500 to-emerald-500 bg-clip-text text-transparent">
                    {{ $world->entityTypes->count() }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>
    </a>
    
    <!-- Relationships -->
    <a href="{{ route('relationships.index', ['world_id' => $world->id]) }}" class="stat-card glass-card card-hover animate-fade-in-up stagger-3" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">{{ __('Relationships') }}</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">
                    {{ $world->relationships->count() }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
        </div>
    </a>
    
    <!-- Tags -->
    <a href="{{ route('tags.index', ['world_id' => $world->id]) }}" class="stat-card glass-card card-hover animate-fade-in-up stagger-4" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">{{ __('Tags') }}</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-amber-500 bg-clip-text text-transparent">
                    {{ $world->tags->count() }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('entities.create', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-blue-100 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-gray-700">{{ __('Add Entity') }}</p>
    </a>
    <a href="{{ route('entity-types.index', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-green-100 flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-gray-700">{{ __('Add Type') }}</p>
    </a>
    <a href="{{ route('relationships.create', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-purple-100 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-gray-700">{{ __('Add Relationship') }}</p>
    </a>
    <a href="{{ route('tags.index', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-gray-700">{{ __('Add Tag') }}</p>
    </a>
</div>

<!-- Recent Entities -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up stagger-5" style="opacity: 0;">
    <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ __('Recent Entities') }}</h2>
            <p class="text-sm text-gray-500">{{ __('Latest additions to this world') }}</p>
        </div>
        <a href="{{ route('entities.index', ['world_id' => $world->id]) }}" class="text-indigo-500 hover:text-indigo-700 font-medium text-sm flex items-center space-x-1">
            <span>{{ __('View All') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    
    <div class="p-6">
        @if($world->entities->isEmpty())
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-gray-500 mb-4">{{ __('No entities yet. Start building your world!') }}</p>
            <a href="{{ route('entities.create', ['world_id' => $world->id]) }}" class="btn-primary inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ __('Create First Entity') }}</span>
            </a>
        </div>
        @else
        <div class="space-y-3">
            @foreach($world->entities->take(10) as $entity)
            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($entity->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $entity->name }}</p>
                        <p class="text-sm text-gray-500">{{ $entity->entityType->name }}</p>
                    </div>
                </div>
                <a href="{{ route('entities.show', $entity) }}" class="opacity-0 group-hover:opacity-100 text-indigo-500 hover:text-indigo-700 transition-all flex items-center space-x-1">
                    <span class="text-sm">{{ __('View') }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
