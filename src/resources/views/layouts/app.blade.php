<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'World Building')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-mesh min-h-screen">
    <!-- Star Rain Effect -->
    <div class="stars-container" id="stars-container"></div>
    
    <!-- Navigation -->
    <nav class="glass-dark sticky top-0 z-50 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-dragon text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                            World Building
                        </span>
                    </a>
                    
                    @auth
                    <!-- Nav Links -->
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-2">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>{{ __('Dashboard') }}</span>
                            </span>
                        </a>
                        <a href="{{ route('worlds.index') }}" class="nav-link {{ request()->routeIs('worlds.*') ? 'active' : '' }}">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ __('Worlds') }}</span>
                            </span>
                        </a>
                        <a href="{{ route('ai-generator.create') }}" class="nav-link {{ request()->routeIs('ai-generator.*') ? 'active' : '' }}">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span>{{ __('AI Generator') }}</span>
                            </span>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>{{ __('Users') }}</span>
                            </span>
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
                
                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/10 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                            <span class="text-sm font-medium">{{ app()->getLocale() == 'vi' ? 'VI' : 'EN' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-40 glass-card rounded-xl shadow-xl overflow-hidden z-50">
                            <a href="{{ route('language.switch', 'en') }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-indigo-50 transition-colors {{ app()->getLocale() == 'en' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
                                <span class="text-lg">🇺🇸</span>
                                <span class="font-medium">English</span>
                                @if(app()->getLocale() == 'en')
                                <svg class="w-4 h-4 ml-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                @endif
                            </a>
                            <a href="{{ route('language.switch', 'vi') }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-indigo-50 transition-colors {{ app()->getLocale() == 'vi' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700' }}">
                                <span class="text-lg">🇻🇳</span>
                                <span class="font-medium">Tiếng Việt</span>
                                @if(app()->getLocale() == 'vi')
                                <svg class="w-4 h-4 ml-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                @endif
                            </a>
                        </div>
                    </div>

                    @auth
                    <div class="flex items-center space-x-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-400">{{ auth()->user()->role->name ?? 'User' }}</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200" title="{{ __('Logout') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">{{ __('Register') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8 animate-fade-in-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success mb-6">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error mb-6">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                © {{ date('Y') }} World Building. Crafted with 💜
            </p>
        </div>
    </footer>

    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Star Rain Effect Script -->
    <script>
        function createStar() {
            const container = document.getElementById('stars-container');
            if (!container) return;
            
            const star = document.createElement('div');
            star.className = 'star';
            star.innerHTML = '✦';
            star.style.left = Math.random() * 100 + 'vw';
            star.style.animationDuration = (Math.random() * 3 + 2) + 's';
            star.style.opacity = Math.random() * 0.7 + 0.3;
            star.style.fontSize = (Math.random() * 10 + 8) + 'px';
            
            container.appendChild(star);
            
            setTimeout(() => {
                star.remove();
            }, 5000);
        }
        
        setInterval(createStar, 150);
    </script>
</body>
</html>
