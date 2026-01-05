<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'World Building')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-2 text-gray-900 font-bold">
                        World Building
                    </a>
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">
                            Dashboard
                        </a>
                        <a href="{{ route('worlds.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                            Worlds
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                            Users
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
                <div class="flex items-center">
                    @auth
                    <span class="text-sm text-gray-700 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-900">Logout</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-900 mr-4">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-gray-900">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
