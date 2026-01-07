@extends('layouts.app')

@section('title', __('Create World'))

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8 animate-fade-in-up">
        <div class="flex items-center space-x-2 text-gray-400 text-sm mb-2">
            <a href="{{ route('worlds.index') }}" class="hover:text-white transition-colors">{{ __('Worlds') }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-white">{{ __('Create New World') }}</span>
        </div>
        <h1 class="text-3xl font-bold text-white">{{ __('Create New World') }}</h1>
        <p class="text-gray-400 mt-2">{{ __('Build a new universe for your stories and characters') }}</p>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-2xl p-8 animate-fade-in-up stagger-1" style="opacity: 0;">
        <form method="POST" action="{{ route('worlds.store') }}" class="space-y-6">
            @csrf
            
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
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="input-modern w-full pl-12"
                        placeholder="{{ __('Enter world name...') }}">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Description') }}</label>
                <textarea name="description" rows="5"
                    class="input-modern w-full resize-none"
                    placeholder="{{ __('Describe your world...') }}">{{ old('description') }}</textarea>
            </div>

            <!-- Tips -->
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-800">{{ __('Tips for creating a great world') }}</p>
                        <ul class="text-sm text-indigo-600 mt-1 space-y-1">
                            <li>• {{ __('Give your world a memorable, unique name') }}</li>
                            <li>• {{ __('Describe the setting, time period, or genre') }}</li>
                            <li>• {{ __('Think about what makes your world special') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('worlds.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="btn-primary flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>{{ __('Create World') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
