@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Create User
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded {{ $user->role->name === 'super_admin' ? 'bg-red-100 text-red-800' : ($user->role->name === 'admin' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ $user->role->name }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <a href="{{ route('users.edit', $user) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
