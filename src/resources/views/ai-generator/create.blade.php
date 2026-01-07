@extends('layouts.app')

@section('title', __('AI World Generator'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8 animate-fade-in-up">
        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl animate-float">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">{{ __('AI World Generator') }}</h1>
        <p class="text-gray-400 max-w-xl mx-auto">{{ __('Describe your world and let AI create everything for you - entities, relationships, tags, and more!') }}</p>
    </div>

    @if(!$hasApiKeys)
    <!-- No API Keys Warning -->
    <div class="glass-card rounded-2xl p-8 text-center animate-fade-in-up">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ __('AI Service Not Configured') }}</h3>
        <p class="text-gray-500 mb-4">{{ __('Please add GEMINI_API_KEYS to your .env file to use this feature.') }}</p>
        <a href="{{ route('worlds.create') }}" class="btn-primary inline-flex items-center space-x-2">
            <span>{{ __('Create World Manually') }}</span>
        </a>
    </div>
    @else
    <!-- Generator Form -->
    <div class="glass-card rounded-2xl p-8 animate-fade-in-up stagger-1" style="opacity: 0;">
        <form method="POST" action="{{ route('ai-generator.generate') }}" id="ai-form">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Describe your world') }} <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="6" required
                    class="input-modern w-full resize-none"
                    placeholder="{{ __('Example: A medieval fantasy world with dragons, knights, and magic. There are several kingdoms at war, ancient ruins hiding powerful artifacts, and mysterious creatures lurking in dark forests...') }}">{{ old('description') }}</textarea>
                <p class="mt-2 text-sm text-gray-500">
                    {{ __('Be as detailed as possible. The more you describe, the better the AI can create your world.') }}
                </p>
            </div>

            <!-- Examples -->
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 mb-3">{{ __('Quick examples (click to use)') }}:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="example-btn tag tag-blue cursor-pointer hover:scale-105" 
                        data-text="{{ __('A cyberpunk city in 2150 with mega-corporations, hackers, AI robots, and underground resistance movements fighting for freedom.') }}">
                        🤖 Cyberpunk
                    </button>
                    <button type="button" class="example-btn tag tag-green cursor-pointer hover:scale-105"
                        data-text="{{ __('A magical school for young wizards with different houses, magical creatures, enchanted objects, and dark forces threatening the peace.') }}">
                        🧙 Magic School
                    </button>
                    <button type="button" class="example-btn tag tag-purple cursor-pointer hover:scale-105"
                        data-text="{{ __('A space opera with multiple alien species, interstellar empires, space pirates, ancient artifacts, and a galactic war brewing.') }}">
                        🚀 Space Opera
                    </button>
                    <button type="button" class="example-btn tag tag-orange cursor-pointer hover:scale-105"
                        data-text="{{ __('A post-apocalyptic world after a zombie outbreak with survivor camps, mutated creatures, scarce resources, and hope for a cure.') }}">
                        🧟 Post-Apocalyptic
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('worlds.index') }}" class="btn-secondary">{{ __('Cancel') }}</a>
                <button type="submit" id="submit-btn" class="btn-primary flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>{{ __('Generate World') }}</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
        <div class="glass-card rounded-2xl p-8 text-center max-w-md mx-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center animate-pulse">
                <svg class="w-8 h-8 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ __('Creating your world...') }}</h3>
            <p class="text-gray-500">{{ __('AI is generating entities, relationships, and tags. This may take 30-60 seconds.') }}</p>
            <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full animate-pulse" style="width: 60%;"></div>
            </div>
        </div>
    </div>

    <!-- Tips -->
    <div class="mt-6 glass-card rounded-xl p-4 animate-fade-in-up stagger-2" style="opacity: 0;">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ __('Tips for better results') }}</p>
                <ul class="text-sm text-gray-600 mt-1 space-y-1">
                    <li>• {{ __('Describe the setting, time period, and atmosphere') }}</li>
                    <li>• {{ __('Mention key characters or factions') }}</li>
                    <li>• {{ __('Include conflicts, mysteries, or goals') }}</li>
                    <li>• {{ __('Specify the genre (fantasy, sci-fi, horror, etc.)') }}</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@if($hasApiKeys)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Example buttons
    document.querySelectorAll('.example-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('description').value = this.dataset.text;
        });
    });
    
    // Form submit - show loading
    document.getElementById('ai-form').addEventListener('submit', function() {
        document.getElementById('loading-overlay').classList.remove('hidden');
        document.getElementById('loading-overlay').classList.add('flex');
        document.getElementById('submit-btn').disabled = true;
    });
});
</script>
@endif
@endsection
