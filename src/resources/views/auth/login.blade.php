@extends('layouts.app')

@section('title', __('Login'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-primary rounded-2xl flex items-center justify-center shadow-2xl animate-float">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary mb-2">{{ __('Welcome Back') }}</h1>
            <p class="text-theme-muted">{{ __('Sign in to continue building your worlds') }}</p>
        </div>

        <!-- Login Form -->
        <div class="glass-card rounded-2xl p-8 animate-fade-in-up stagger-1" style="opacity: 0;">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-theme-secondary mb-2">{{ __('Email Address') }}</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="input-modern w-full"
                            placeholder="you@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-theme-secondary mb-2">{{ __('Password') }}</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" name="password" required
                            class="input-modern w-full"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-theme-secondary">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3 text-center flex items-center justify-center space-x-2">
                    <span>{{ __('Sign In') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-slate-800 text-theme-muted rounded">{{ __('New to World Building?') }}</span>
                </div>
            </div>

            <!-- Register Link -->
            <a href="{{ route('register') }}" class="btn-secondary w-full py-3 text-center block">
                {{ __('Create an Account') }}
            </a>
        </div>

        <!-- Demo Accounts -->
        <div class="mt-6 glass-card rounded-xl p-4 animate-fade-in-up stagger-2" style="opacity: 0;">
            <p class="text-sm text-theme-secondary text-center mb-3">{{ __('Demo Accounts') }}:</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <p class="font-semibold text-theme-primary">{{ __('Admin') }}</p>
                    <p class="text-theme-muted">admin@example.com</p>
                    <p class="text-theme-muted">password</p>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <p class="font-semibold text-theme-primary">{{ __('User') }}</p>
                    <p class="text-theme-muted">user@example.com</p>
                    <p class="text-theme-muted">password</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
