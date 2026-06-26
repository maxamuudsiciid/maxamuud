@extends('layouts.app')
@section('title','Edit User')
@section('page-title','User Management')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div><h2 class="page-heading">Edit User</h2><p class="page-sub">Update account: {{ $user->name }}</p></div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-user-edit me-2 text-danger"></i>Edit Account — {{ $user->name }}</div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (read-only)</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required {{ $user->id===Auth::id()?'disabled':'' }}>
                            @foreach(['admin','staff','donor','hospital'] as $r)
                                <option value="{{ $r }}" {{ old('role',$user->role)===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                        @if($user->id===Auth::id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <small class="text-muted">You cannot change your own role.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="New password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
