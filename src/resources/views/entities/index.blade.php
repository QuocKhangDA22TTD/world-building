@extends('layouts.app')

@section('title', 'Entities - ' . $world->name)

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in-up">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <div class="flex items-center space-x-2 text-theme-muted text-sm mb-2">
                <a href="{{ route('worlds.index') }}" class="hover:text-theme-primary transition-colors">Worlds</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('worlds.show', $world) }}" class="hover:text-theme-primary transition-colors">{{ $world->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-theme-secondary">Entities</span>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary">Entities</h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('entities.create', ['world_id' => $world->id]) }}" class="btn-primary flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Entity</span>
            </a>
            <a href="{{ route('worlds.show', $world) }}" class="btn-secondary flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="glass-card rounded-xl p-4">
        <input type="hidden" name="world_id" value="{{ $world->id }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Search entities..." value="{{ request('search') }}"
                    class="input-modern w-full pl-10">
            </div>
            
            <select name="type_id" class="input-modern">
                <option value="">All Types</option>
                @foreach($types as $type)
                <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>

            <select name="tag_id" class="input-modern">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter</span>
            </button>
        </div>
    </form>
</div>

@if($entities->isEmpty())
<!-- Empty State -->
<div class="glass-card rounded-2xl p-12 text-center animate-fade-in-up">
    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-blue-500/20 to-cyan-500/20 flex items-center justify-center">
        <svg class="w-12 h-12 text-blue-500 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-theme-primary mb-2">No entities found</h3>
    <p class="text-theme-muted mb-6">Start populating your world with characters, locations, items, and more!</p>
    <a href="{{ route('entities.create', ['world_id' => $world->id]) }}" class="btn-primary inline-flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Create First Entity</span>
    </a>
</div>
@else
<!-- Entities Table -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="table-modern min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left">Entity</th>
                    <th class="px-6 py-4 text-left">Type</th>
                    <th class="px-6 py-4 text-left">Tags</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($entities as $index => $entity)
                <tr class="animate-fade-in-up" style="opacity: 0; animation-delay: {{ $index * 0.03 }}s;">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow">
                                {{ strtoupper(substr($entity->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-theme-primary">{{ $entity->name }}</p>
                                <p class="text-xs text-theme-muted max-w-xs truncate">{{ Str::limit($entity->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="tag tag-purple">{{ $entity->entityType->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @forelse($entity->tags as $tag)
                            <span class="tag tag-blue text-xs">{{ $tag->name }}</span>
                            @empty
                            <span class="text-theme-muted text-sm">No tags</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('entities.show', $entity) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('entities.edit', $entity) }}" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('entities.destroy', $entity) }}" class="inline" onsubmit="return confirm('Delete this entity?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $entities->links() }}
</div>
@endif
@endsection
