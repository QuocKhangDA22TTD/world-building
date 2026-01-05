@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Login</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">Register</a>
    </p>
</div>
@endsection
