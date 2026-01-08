@extends('layouts.app')

@section('title', __('Register'))

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
            <h1 class="text-3xl font-bold text-theme-primary mb-2">{{ __('Create Account') }}</h1>
            <p class="text-theme-muted">{{ __('Start building your own worlds today') }}</p>
        </div>

        <!-- Register Form -->
        <div class="glass-card rounded-2xl p-8 animate-fade-in-up stagger-1" style="opacity: 0;">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-theme-secondary mb-2">{{ __('Full Name') }}</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="input-modern w-full"
                            placeholder="John Doe">
                    </div>
                </div>

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

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-theme-secondary mb-2">{{ __('Confirm Password') }}</label>
                    <div class="input-icon-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <input type="password" name="password_confirmation" required
                            class="input-modern w-full"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" required class="w-4 h-4 mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-theme-secondary">
                        {{ __('I agree to the') }} <a href="#" class="text-indigo-600 hover:text-indigo-700">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="#" class="text-indigo-600 hover:text-indigo-700">{{ __('Privacy Policy') }}</a>
                    </span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3 text-center flex items-center justify-center space-x-2">
                    <span>{{ __('Create Account') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200 dark:border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-slate-800 text-theme-muted rounded">{{ __('Already have an account?') }}</span>
                </div>
            </div>

            <!-- Login Link -->
            <a href="{{ route('login') }}" class="btn-secondary w-full py-3 text-center block">
                {{ __('Sign In Instead') }}
            </a>
        </div>
    </div>
</div>
@endsection
