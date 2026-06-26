@extends('layouts.app')
@section('title','Settings')
@section('page-title','Settings')
@section('breadcrumb')
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Account Settings</h2>
        <p class="page-sub">Manage your profile and preferences</p>
    </div>
</div>

<div class="row g-4">
    {{-- Profile Settings --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                @if(Auth::user()->image)
                    <img src="{{ asset('storage/profiles/'.Auth::user()->image) }}" alt="Profile Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;cursor:pointer;" onclick="window.open(this.src, '_blank')">
                @else
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#7f1d1d);display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px;font-weight:700;margin:0 auto 16px;">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </div>
                @endif
                <h5 class="fw-700 mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1" style="font-size:13px;">{{ Auth::user()->email }}</p>
                <span class="badge-status badge-{{ Auth::user()->role === 'admin' ? 'safe' : 'pending' }}">{{ ucfirst(Auth::user()->role) }}</span>
                <div class="mt-3 text-muted" style="font-size:12px;">Member since {{ Auth::user()->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Update Profile --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user-edit me-2 text-danger"></i>Update Profile</div>
            <div class="card-body">
                <form action="{{ route('settings.updateProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">Profile Photo (Max 2MB)</label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="imagePreview" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center;">
                                    @if(Auth::user()->image)
                                        <img src="{{ asset('storage/profiles/'.Auth::user()->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user text-secondary"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="image" id="profilePhotoInput" class="form-control @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if(Auth::user()->image)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_image" id="removeImage" value="1">
                                        <label class="form-check-label text-danger" for="removeImage" style="font-size: 13px; cursor: pointer;">
                                            <i class="fas fa-trash-alt me-1"></i> Remove current profile picture
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-blood mt-3"><i class="fas fa-save me-2"></i>Update Profile</button>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-lock me-2 text-danger"></i>Change Password</div>
            <div class="card-body">
                <form action="{{ route('settings.updatePassword') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Your current password" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                class="form-control" placeholder="Repeat new password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-blood mt-3"><i class="fas fa-key me-2"></i>Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('imagePreview');
            previewDiv.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
