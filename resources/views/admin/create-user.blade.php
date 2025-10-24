@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-xl font-semibold mb-4">Create User</h1>

    @if (session('success'))
        <div class="mb-3 text-green-700">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <ul class="mb-3 text-red-600 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.createUser') }}">
        @csrf
        <div class="mb-3">
            <label class="block mb-1">Name</label>
            <input name="name" class="w-full border rounded p-2" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Role</label>
            <select name="role" class="w-full border rounded p-2">
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded border">
            Create
        </button>
    </form>
</div>
@endsection
