@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-brand">
    <div class="brand-logo"><i class="fa-solid fa-droplet"></i></div>
    <h1>BloodBank MS</h1>
    <p>Create your account</p>
</div>

<div class="auth-card">
    <h2>Create Account</h2>
    <p class="sub">Fill in the details below to register</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="name">Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user fa-sm"></i></span>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder=>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope fa-sm"></i></span>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder=>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="role">Account Type</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-id-badge fa-sm"></i></span>
                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Select Role...</option>
                    <option value="donor" {{ old('role') == 'donor' ? 'selected' : '' }}>Donor</option>
                    <option value="hospital" {{ old('role') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    {{-- Admin role hidden from public registration for security --}}
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder=>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-6">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-control" placeholder=>
            </div>
        </div>

        <button type="submit" class="btn-auth">
            <i class="fas fa-user-plus me-2"></i> Create Account
        </button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>
@endsection
