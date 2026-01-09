@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-theme-primary mb-6">Create New User</h1>

    <form method="POST" action="{{ route('users.store') }}" class="glass-card p-6 rounded-lg">
        @csrf
        
        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="input-modern w-full">
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="input-modern w-full">
        </div>

        <div class="mb-4">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" required class="input-modern w-full">
        </div>

        <div class="mb-6">
            <label class="block text-theme-secondary text-sm font-bold mb-2">Role</label>
            <select name="role_id" required class="input-modern w-full">
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('users.index') }}" class="btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-primary">
                Create User
            </button>
        </div>
    </form>
</div>
@endsection
