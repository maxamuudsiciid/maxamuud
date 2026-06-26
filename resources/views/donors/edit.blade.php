@extends('layouts.app')
@section('title', 'Edit Donor')
@section('page-title', 'Edit Donor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Donor</h2>
        <p class="page-sub">Update donor profile: {{ $donor->full_name }}</p>
    </div>
    <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-user-edit me-2 text-danger"></i>Donor Information</div>
            <div class="card-body">
                <form action="{{ route('donors.update', $donor->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                                value="{{ old('full_name', $donor->full_name) }}" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ $donor->email }}" disabled>
                            <small class="text-muted">Email cannot be changed here</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="Male" {{ old('gender',$donor->gender)=='Male'?'selected':'' }}>Male</option>
                                <option value="Female" {{ old('gender',$donor->gender)=='Female'?'selected':'' }}>Female</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth', $donor->date_of_birth ? $donor->date_of_birth->format('Y-m-d') : '') }}" required>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" required>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group',$donor->blood_group)==$bg?'selected':'' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $donor->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Donation Date</label>
                            <input type="date" name="last_donation_date" class="form-control @error('last_donation_date') is-invalid @enderror"
                                value="{{ old('last_donation_date', $donor->last_donation_date ? $donor->last_donation_date->format('Y-m-d') : '') }}">
                            @error('last_donation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="2" required>{{ old('address', $donor->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if(in_array(Auth::user()->role, ['admin','staff']))
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Active" {{ old('status',$donor->status)=='Active'?'selected':'' }}>Active</option>
                                <option value="Inactive" {{ old('status',$donor->status)=='Inactive'?'selected':'' }}>Inactive</option>
                                <option value="Deferred" {{ old('status',$donor->status)=='Deferred'?'selected':'' }}>Deferred</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update Donor</button>
                        <a href="{{ route('donors.show', $donor->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
