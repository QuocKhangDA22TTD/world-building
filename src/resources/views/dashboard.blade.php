@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold text-white mb-2">
        {{ __('Welcome back') }}, <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>! 👋
    </h1>
    <p class="text-gray-400">{{ __("Here's what's happening with your worlds today.") }}</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- My Worlds -->
    <div class="stat-card glass-card animate-fade-in-up stagger-1" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">{{ __('My Worlds') }}</p>
                <p class="text-4xl font-bold bg-gradient-to-r from-blue-500 to-cyan-500 bg-clip-text text-transparent">
                    {{ $worlds->count() }}
                </p>
            </div>
            <div class="stat-icon bg-gradient-to-r from-blue-500 to-cyan-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-500 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                {{ __('Active') }}
            </span>
        </div>
    </div>
    
    <!-- Total Entities -->
    <div class="stat-card glass-card animate-fade-in-up stagger-2" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">{{ __('Total Entities') }}</p>
                <p class="text-4xl font-bold bg-gradient-to-r from-green-500 to-emerald-500 bg-clip-text text-transparent">
                    {{ $totalEntities }}
                </p>
            </div>
            <div class="stat-icon bg-gradient-to-r from-green-500 to-emerald-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-500">
            <span>{{ __('Across all worlds') }}</span>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- Total Users -->
    <div class="stat-card glass-card animate-fade-in-up stagger-3" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">{{ __('Total Users') }}</p>
                <p class="text-4xl font-bold bg-gradient-to-r from-purple-500 to-pink-500 bg-clip-text text-transparent">
                    {{ $totalUsers }}
                </p>
            </div>
            <div class="stat-icon bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm text-gray-500">
            <span>{{ __('Registered users') }}</span>
        </div>
    </div>
    @endif
</div>

<!-- My Worlds Section -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up stagger-4" style="opacity: 0;">
    <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ __('My Worlds') }}</h2>
            <p class="text-sm text-gray-500">{{ __('Manage your creative universes') }}</p>
        </div>
        <a href="{{ route('worlds.create') }}" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('Create World') }}</span>
        </a>
    </div>
    
    <div class="p-6">
        @if($worlds->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">{{ __('No worlds yet') }}</h3>
            <p class="text-gray-500 mb-4">{{ __('Start building your first world and bring your imagination to life!') }}</p>
            <a href="{{ route('worlds.create') }}" class="btn-primary inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>{{ __('Create Your First World') }}</span>
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($worlds as $index => $world)
            <div class="world-card border border-gray-100 p-5 animate-fade-in-up" style="animation-delay: {{ ($index + 1) * 0.1 }}s;">
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ strtoupper(substr($world->name, 0, 1)) }}
                        </div>
                        <span class="tag tag-blue">{{ $world->entities_count }} {{ __('entities') }}</span>
                    </div>
                    
                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $world->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($world->description, 80) ?: __('No description') }}</p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-400">
                            {{ $world->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('worlds.show', $world) }}" class="text-indigo-500 hover:text-indigo-700 font-medium text-sm flex items-center space-x-1 group">
                            <span>{{ __('Explore') }}</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
