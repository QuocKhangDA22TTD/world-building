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
<<<<<<< HEAD
                <h1 class="text-3xl font-bold text-theme-primary">{{ $world->name }}</h1>
                <p class="text-theme-secondary">{{ __('Created') }} {{ $world->created_at->diffForHumans() }}</p>
=======
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $world->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Created') }} {{ $world->created_at->diffForHumans() }}</p>
>>>>>>> e6f6c3a1db5feb3029cb2fd333da959111fd4873
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <!-- AI Chat Toggle Button -->
            <button id="ai-chat-toggle" class="btn-primary flex items-center space-x-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>{{ __('AI Assistant') }}</span>
            </button>
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

<!-- AI Chat Panel (Hidden by default) -->
<div id="ai-chat-panel" class="hidden fixed right-0 top-1/2 -translate-y-1/2 w-full sm:w-96 z-50" style="resize: both; overflow: hidden;">
    <!-- Drag Handle Left -->
    <div class="absolute top-0 -left-1 bottom-0 w-1 bg-gradient-to-b from-purple-500 via-pink-500 to-purple-500 cursor-ew-resize hover:w-1.5 transition-all" id="ai-resize-left" title="Kéo để thay đổi chiều rộng"></div>
    
    <div class="h-96 flex flex-col bg-slate-900/95 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl">
        <!-- Drag Handle Top -->
        <div class="flex-none h-2 bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500 cursor-ns-resize hover:h-3 transition-all rounded-t-2xl" id="ai-resize-top" title="Kéo để thay đổi chiều cao"></div>
        
        <!-- Chat Header -->
        <div class="flex-none p-4 border-b border-white/10 flex items-center justify-between bg-slate-800/50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-white">{{ __('AI Assistant') }}</h3>
                    <p class="text-xs text-gray-400">{{ __('Ask or modify your world') }}</p>
                </div>
            </div>
            <button id="ai-chat-close" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Chat Messages -->
        <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" style="min-height: 0;">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="glass-card rounded-xl rounded-tl-none p-3 max-w-[85%]">
                    <p class="text-sm text-gray-700">{{ __('Hello! I can help you modify your world. You can ask me to:') }}</p>
                    <ul class="text-sm text-gray-600 mt-2 space-y-1">
                        <li>• {{ __('Add new characters, locations, items') }}</li>
                        <li>• {{ __('Create relationships between entities') }}</li>
                        <li>• {{ __('Update descriptions or details') }}</li>
                        <li>• {{ __('Answer questions about your world') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Pending Changes (Hidden by default) -->
        <div id="ai-pending-changes" class="hidden flex-none border-t border-white/10 p-4 bg-yellow-500/10">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-yellow-400">{{ __('Pending Changes') }}</span>
                <div class="flex space-x-2">
                    <button id="ai-apply-changes" class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                        {{ __('Apply') }}
                    </button>
                    <button id="ai-reject-changes" class="px-3 py-1 text-xs bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        {{ __('Reject') }}
                    </button>
                </div>
            </div>
            <p id="ai-changes-summary" class="text-xs text-gray-400"></p>
        </div>
        
        <!-- Chat Input - Fixed at bottom -->
        <div class="flex-none p-4 border-t border-white/10 bg-slate-800/50">
            <form id="ai-chat-form" class="flex space-x-2">
                <input type="text" id="ai-chat-input" 
                    class="flex-1 px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors"
                    placeholder="{{ __('Type your message...') }}"
                    autocomplete="off">
                <button type="submit" id="ai-chat-send" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl transition-all disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Overlay for mobile -->
<div id="ai-chat-overlay" class="hidden fixed inset-0 bg-black/50 z-40"></div>

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
        <p class="font-medium text-theme-secondary">{{ __('Add Entity') }}</p>
    </a>
    <a href="{{ route('entity-types.index', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-green-100 flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-theme-secondary">{{ __('Add Type') }}</p>
    </a>
    <a href="{{ route('relationships.create', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-purple-100 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-theme-secondary">{{ __('Add Relationship') }}</p>
    </a>
    <a href="{{ route('tags.index', ['world_id' => $world->id]) }}" class="glass-card rounded-xl p-4 text-center card-hover group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-orange-100 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <p class="font-medium text-theme-secondary">{{ __('Add Tag') }}</p>
    </a>
</div>

<!-- Recent Entities -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up stagger-5" style="opacity: 0;">
    <div class="px-6 py-5 border-b border-gray-200/50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-theme-primary">{{ __('Recent Entities') }}</h2>
            <p class="text-sm text-theme-muted">{{ __('Latest additions to this world') }}</p>
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
            <p class="text-theme-muted mb-4">{{ __('No entities yet. Start building your world!') }}</p>
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
                        <p class="font-medium text-theme-primary">{{ $entity->name }}</p>
                        <p class="text-sm text-theme-muted">{{ $entity->entityType->name }}</p>
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

<!-- AI Chat JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('ai-chat-toggle');
    const chatPanel = document.getElementById('ai-chat-panel');
    const chatClose = document.getElementById('ai-chat-close');
    const chatOverlay = document.getElementById('ai-chat-overlay');
    const chatForm = document.getElementById('ai-chat-form');
    const chatInput = document.getElementById('ai-chat-input');
    const chatMessages = document.getElementById('ai-chat-messages');
    const chatSend = document.getElementById('ai-chat-send');
    const pendingChanges = document.getElementById('ai-pending-changes');
    const changesSummary = document.getElementById('ai-changes-summary');
    const applyBtn = document.getElementById('ai-apply-changes');
    const rejectBtn = document.getElementById('ai-reject-changes');
    
    let chatHistory = [];
    let currentChanges = null;
    const worldId = {{ $world->id }};
    const csrfToken = '{{ csrf_token() }}';
    
    // Toggle chat panel
    function openChat() {
        chatPanel.classList.remove('hidden');
        chatOverlay.classList.remove('hidden');
        chatInput.focus();
    }
    
    function closeChat() {
        chatPanel.classList.add('hidden');
        chatOverlay.classList.add('hidden');
    }
    
    chatToggle.addEventListener('click', openChat);
    chatClose.addEventListener('click', closeChat);
    chatOverlay.addEventListener('click', closeChat);
    
    // Add message to chat
    function addMessage(content, isUser = false, isLoading = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start space-x-3 ${isUser ? 'flex-row-reverse space-x-reverse' : ''}`;
        
        const avatar = isUser 
            ? `<div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-sm font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
               </div>`
            : `<div class="w-8 h-8 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
               </div>`;
        
        const bubbleClass = isUser 
            ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-tr-none' 
            : 'glass-card rounded-tl-none';
        
        const loadingHtml = isLoading 
            ? `<div class="flex space-x-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
               </div>`
            : `<p class="text-sm ${isUser ? '' : 'text-gray-700'}">${escapeHtml(content)}</p>`;
        
        messageDiv.innerHTML = `
            ${avatar}
            <div class="${bubbleClass} rounded-xl p-3 max-w-[85%]">
                ${loadingHtml}
            </div>
        `;
        
        if (isLoading) {
            messageDiv.id = 'loading-message';
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        return messageDiv;
    }
    
    function removeLoadingMessage() {
        const loading = document.getElementById('loading-message');
        if (loading) loading.remove();
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Show pending changes
    function showPendingChanges(changes) {
        currentChanges = changes;
        const summary = changes.action_summary || '{{ __("AI wants to make changes to your world") }}';
        changesSummary.textContent = summary;
        pendingChanges.classList.remove('hidden');
    }
    
    function hidePendingChanges() {
        currentChanges = null;
        pendingChanges.classList.add('hidden');
    }
    
    // Send message
    async function sendMessage(message) {
        if (!message.trim()) return;
        
        // Add user message
        addMessage(message, true);
        chatHistory.push({ role: 'user', content: message });
        
        // Show loading
        addMessage('', false, true);
        chatInput.disabled = true;
        chatSend.disabled = true;
        
        try {
            const response = await fetch(`/api/worlds/${worldId}/ai-chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    chat_history: chatHistory.slice(-10) // Last 10 messages
                })
            });
            
            removeLoadingMessage();
            
            const data = await response.json();
            
            if (data.success) {
                addMessage(data.response, false);
                chatHistory.push({ role: 'assistant', content: data.response });
                
                // If there are pending changes
                if (data.requires_confirmation && data.changes) {
                    showPendingChanges(data.changes);
                }
            } else {
                addMessage(data.error || '{{ __("An error occurred") }}', false);
            }
        } catch (error) {
            removeLoadingMessage();
            addMessage('{{ __("Failed to connect to AI service") }}', false);
            console.error('AI Chat Error:', error);
        }
        
        chatInput.disabled = false;
        chatSend.disabled = false;
        chatInput.focus();
    }
    
    // Apply changes
    async function applyChanges() {
        if (!currentChanges) return;
        
        applyBtn.disabled = true;
        applyBtn.textContent = '{{ __("Applying...") }}';
        
        try {
            const response = await fetch(`/api/worlds/${worldId}/ai-apply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    changes: currentChanges
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                addMessage('{{ __("Changes applied successfully! Refreshing page...") }}', false);
                hidePendingChanges();
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                addMessage(data.error || '{{ __("Failed to apply changes") }}', false);
            }
        } catch (error) {
            addMessage('{{ __("Failed to apply changes") }}', false);
            console.error('Apply Changes Error:', error);
        }
        
        applyBtn.disabled = false;
        applyBtn.textContent = '{{ __("Apply") }}';
    }
    
    // Event listeners
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (message) {
            sendMessage(message);
            chatInput.value = '';
        }
    });
    
    applyBtn.addEventListener('click', applyChanges);
    
    rejectBtn.addEventListener('click', function() {
        hidePendingChanges();
        addMessage('{{ __("Changes rejected.") }}', false);
    });
    
    // Resize functionality
    setTimeout(() => {
        const resizeTop = document.getElementById('ai-resize-top');
        const resizeLeft = document.getElementById('ai-resize-left');
        let isResizing = false;
        let resizeMode = null;
        let startY, startX, startHeight, startWidth;
        
        function startResizeTop(e) {
            e.preventDefault();
            isResizing = true;
            resizeMode = 'top';
            startY = e.clientY;
            startHeight = chatPanel.offsetHeight;
            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);
            document.body.style.cursor = 'ns-resize';
        }
        
        function startResizeLeft(e) {
            e.preventDefault();
            isResizing = true;
            resizeMode = 'left';
            startX = e.clientX;
            startWidth = chatPanel.offsetWidth;
            document.addEventListener('mousemove', handleResize);
            document.addEventListener('mouseup', stopResize);
            document.body.style.cursor = 'ew-resize';
        }
        
        function handleResize(e) {
            if (!isResizing) return;
            
            if (resizeMode === 'top') {
                const delta = e.clientY - startY;
                const newHeight = startHeight - delta;
                if (newHeight > 200 && newHeight < window.innerHeight - 100) {
                    chatPanel.style.height = newHeight + 'px';
                }
            } else if (resizeMode === 'left') {
                const delta = e.clientX - startX;
                const newWidth = startWidth + delta;
                if (newWidth > 300 && newWidth < window.innerWidth - 50) {
                    chatPanel.style.width = newWidth + 'px';
                }
            }
        }
        
        function stopResize() {
            isResizing = false;
            resizeMode = null;
            document.removeEventListener('mousemove', handleResize);
            document.removeEventListener('mouseup', stopResize);
            document.body.style.cursor = 'auto';
        }
        
        if (resizeTop) {
            resizeTop.addEventListener('mousedown', startResizeTop);
        }
        if (resizeLeft) {
            resizeLeft.addEventListener('mousedown', startResizeLeft);
        }
    }, 100);
    
    // Keyboard shortcut to open chat (Ctrl+Shift+A)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'A') {
            e.preventDefault();
            if (chatPanel.classList.contains('hidden')) {
                openChat();
            } else {
                closeChat();
            }
        }
        // Escape to close
        if (e.key === 'Escape' && !chatPanel.classList.contains('hidden')) {
            closeChat();
        }
    });
});
</script>

<style>
#ai-chat-panel {
    animation: slide-in-right 0.3s ease-out;
}

@keyframes slide-in-right {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

#ai-chat-messages::-webkit-scrollbar {
    width: 6px;
}

#ai-chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

#ai-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

#ai-chat-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
@endsection
