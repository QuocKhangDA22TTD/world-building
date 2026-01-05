@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">My Worlds</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $worlds->count() }}</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Total Entities</h3>
        <p class="text-3xl font-bold text-green-600">{{ $totalEntities }}</p>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
        <p class="text-3xl font-bold text-purple-600">{{ $totalUsers }}</p>
    </div>
    @endif
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold">My Worlds</h2>
        <a href="{{ route('worlds.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Create World
        </a>
    </div>
    <div class="p-6">
        @if($worlds->isEmpty())
        <p class="text-gray-500">No worlds yet. Create your first world!</p>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($worlds as $world)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <h3 class="font-semibold text-lg mb-2">{{ $world->name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($world->description, 100) }}</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ $world->entities_count }} entities</span>
                    <a href="{{ route('worlds.show', $world) }}" class="text-blue-500 hover:text-blue-700">View →</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
