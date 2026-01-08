@extends('layouts.app')

@section('title', __('My Worlds'))

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-theme-primary mb-2">{{ __('My Worlds') }}</h1>
        <p class="text-theme-muted">{{ __('Explore and manage your creative universes') }}</p>
    </div>
    <a href="{{ route('worlds.create') }}" class="btn-primary flex items-center space-x-2 w-fit">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>{{ __('Create World') }}</span>
    </a>
</div>

@if($worlds->isEmpty())
<!-- Empty State -->
<div class="glass-card rounded-2xl p-12 text-center animate-fade-in-up">
    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 flex items-center justify-center">
        <svg class="w-12 h-12 text-indigo-500 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-theme-primary mb-2">{{ __('No worlds yet') }}</h3>
    <p class="text-theme-muted mb-6 max-w-md mx-auto">{{ __('Start building your first world and bring your imagination to life!') }}</p>
    <a href="{{ route('worlds.create') }}" class="btn-primary inline-flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>{{ __('Create Your First World') }}</span>
    </a>
</div>
@else
<!-- Worlds Table -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up">
    <div class="overflow-x-auto">
        <table class="table-modern min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left">{{ __('World') }}</th>
                    <th class="px-6 py-4 text-left">{{ __('Description') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Entities') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Types') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                @foreach($worlds as $index => $world)
                <tr class="animate-fade-in-up" style="opacity: 0; animation-delay: {{ $index * 0.05 }}s;">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg">
                                {{ strtoupper(substr($world->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-theme-primary">{{ $world->name }}</p>
                                <p class="text-xs text-theme-muted">{{ $world->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-theme-secondary text-sm max-w-xs truncate">{{ $world->description ?: __('No description') }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="tag tag-blue">{{ $world->entities_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="tag tag-purple">{{ $world->entity_types_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('worlds.show', $world) }}" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="{{ __('View') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('worlds.edit', $world) }}" class="p-2 text-green-500 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('worlds.destroy', $world) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this world?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="{{ __('Delete') }}">
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
@endif
@endsection
