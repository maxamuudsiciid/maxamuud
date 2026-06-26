@extends('layouts.app')
@section('title','Edit Hospital')
@section('page-title','Hospitals')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('hospitals.index') }}">Hospitals</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Hospital</h2>
        <p class="page-sub">Update: {{ $hospital->hospital_name }}</p>
    </div>
    <a href="{{ route('hospitals.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Edit: {{ $hospital->hospital_name }}</div>
            <div class="card-body">
                <form action="{{ route('hospitals.update', $hospital->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Hospital Name <span class="text-danger">*</span></label>
                            <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror"
                                value="{{ old('hospital_name', $hospital->hospital_name) }}" required>
                            @error('hospital_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email (read-only)</label>
                            <input type="email" class="form-control" value="{{ $hospital->email }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $hospital->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                                value="{{ old('contact_person', $hospital->contact_person) }}" required>
                            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="2" required>{{ old('address', $hospital->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update Hospital</button>
                        <a href="{{ route('hospitals.show', $hospital->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
