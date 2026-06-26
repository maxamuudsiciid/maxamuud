@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-brand">
    <div class="brand-logo"><i class="fa-solid fa-droplet"></i></div>
    <h1>BLOOD</h1>
    <p>Blood Donation Management System</p>
</div>

<div class="auth-card">
    <h2>Welcome </h2>
    <p class="sub">Sign in to your account to continue</p>



    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder=>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock fa-sm"></i></span>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder=>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13px;">Remember me</label>
            </div>
            <a href="#" style="font-size:13px;color:#dc2626;font-weight:500;text-decoration:none;">Forgot password?</a>
        </div>

        <button type="submit" class="btn-auth">
            <i class="fas fa-sign-in-alt me-2"></i> Sign In
        </button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </div>
</div>
@endsection
