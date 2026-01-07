@extends('layouts.app')

@section('title', __('Users Management'))

@section('content')
<!-- Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in-up">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2">{{ __('Users Management') }}</h1>
        <p class="text-gray-400">{{ __('Manage all registered users') }}</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn-primary flex items-center space-x-2 w-fit">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        <span>{{ __('Add User') }}</span>
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card glass-card animate-fade-in-up stagger-1" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ __('Total Users') }}</p>
                <p class="text-3xl font-bold text-gray-800">{{ $users->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card glass-card animate-fade-in-up stagger-2" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ __('Admins') }}</p>
                <p class="text-3xl font-bold text-purple-600">{{ $users->filter(fn($u) => $u->role && in_array($u->role->name, ['admin', 'super_admin']))->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card glass-card animate-fade-in-up stagger-3" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ __('Regular Users') }}</p>
                <p class="text-3xl font-bold text-green-600">{{ $users->filter(fn($u) => !$u->role || $u->role->name === 'user')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="stat-card glass-card animate-fade-in-up stagger-4" style="opacity: 0;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ __('Total Worlds') }}</p>
                <p class="text-3xl font-bold text-orange-600">{{ $users->sum(fn($u) => $u->worlds->count()) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up stagger-5" style="opacity: 0;">
    <div class="overflow-x-auto">
        <table class="table-modern min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left">{{ __('User') }}</th>
                    <th class="px-6 py-4 text-left">{{ __('Email') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Role') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Worlds') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Joined') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $index => $user)
                <tr class="animate-fade-in-up" style="opacity: 0; animation-delay: {{ $index * 0.05 }}s;">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                @if($user->id === auth()->id())
                                <span class="text-xs text-indigo-500">({{ __('You') }})</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $roleName = $user->role->name ?? 'user';
                            $roleColors = [
                                'super_admin' => 'tag-orange',
                                'admin' => 'tag-purple',
                                'moderator' => 'tag-green',
                                'user' => 'tag-blue',
                            ];
                        @endphp
                        <span class="tag {{ $roleColors[$roleName] ?? 'tag-blue' }}">
                            {{ ucfirst(str_replace('_', ' ', $roleName)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-semibold text-gray-700">{{ $user->worlds->count() }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('users.edit', $user) }}" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('{{ __('Delete this user?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
