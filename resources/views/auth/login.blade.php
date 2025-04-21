@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                required 
                                autofocus>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                required>
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <!-- Remember Me -->
                        <div class="form-group form-check">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember" 
                                class="form-check-input" 
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Forgot Password -->
                        <div class="col-12 text-center mt-3">
                            <a class="btn" href="{{ route('password.request') }}" class="text-decoration-none">Forgot your password?</a>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                Login
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mt-3">
                            <a class="btn" href="{{ route('register') }}">Register</a>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


