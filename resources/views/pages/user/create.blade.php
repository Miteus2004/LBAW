@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New User</h2>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                name="username" 
                class="form-control @error('username') is-invalid @enderror" 
                id="username" 
                value="{{ old('username') }}" 
                required
            >
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail Address</label>
            <input 
                type="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                value="{{ old('email') }}" 
                required
            >
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                required
            >
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                class="form-control" 
                id="password_confirmation" 
                required
            >
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select 
                name="role" 
                class="form-control @error('role') is-invalid @enderror" 
                id="role" 
                required
            >
                <option value="authenticated_user" {{ old('role') == 'authenticated_user' ? 'selected' : '' }}>Student</option>
                <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                <option value="administrator" {{ old('role') == 'administrator' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">Create User</button>
    </form>
</div>
@endsection