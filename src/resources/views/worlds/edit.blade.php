@extends('layouts.app')

@section('title', __('Edit World'))

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 animate-fade-in-up">
        <div class="flex items-center space-x-2 text-gray-400 text-sm mb-2">
            <a href="{{ route('worlds.index') }}" class="hover:text-white transition-colors">{{ __('Worlds') }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('worlds.show', $world) }}" class="hover:text-white transition-colors">{{ $world->name }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-white">{{ __('Edit') }}</span>
        </div>
        <h1 class="text-3xl font-bold text-white">{{ __('Edit World') }}</h1>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-2xl p-8 animate-fade-in-up stagger-1" style="opacity: 0;">
        <form method="POST" action="{{ route('worlds.update', $world) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- World Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('World Name') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $world->name) }}" required
                        class="input-modern w-full pl-12"
                        placeholder="{{ __('Enter world name...') }}">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                <textarea name="description" rows="5"
                    class="input-modern w-full resize-none"
                    placeholder="{{ __('Describe your world...') }}">{{ old('description', $world->description) }}</textarea>
            </div>

            <!-- Stats -->
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm font-medium text-gray-700 mb-3">{{ __('World Statistics') }}</p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-indigo-600">{{ $world->entities->count() }}</p>
                        <p class="text-xs text-gray-500">{{ __('Entities') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $world->entityTypes->count() }}</p>
                        <p class="text-xs text-gray-500">{{ __('Types') }}</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-purple-600">{{ $world->relationships->count() }}</p>
                        <p class="text-xs text-gray-500">{{ __('Relationships') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('worlds.destroy', $world) }}" onsubmit="return confirm('{{ __('Are you sure? This will delete all entities, relationships, and tags in this world.') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>{{ __('Delete World') }}</span>
                    </button>
                </form>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('worlds.show', $world) }}" class="btn-secondary">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn-primary flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ __('Save Changes') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
