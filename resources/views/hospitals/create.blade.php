@extends('layouts.app')
@section('title','Add Hospital')
@section('page-title','Hospitals')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('hospitals.index') }}">Hospitals</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Add Hospital</h2>
        <p class="page-sub">Register a new hospital in the system</p>
    </div>
    <a href="{{ route('hospitals.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-hospital me-2 text-danger"></i>Hospital Information</div>
            <div class="card-body">
                <form action="{{ route('hospitals.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Hospital Name <span class="text-danger">*</span></label>
                            <input type="text" name="hospital_name" class="form-control @error('hospital_name') is-invalid @enderror"
                                value="{{ old('hospital_name') }}" placeholder=>
                            @error('hospital_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder=>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder=>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror"
                                value="{{ old('contact_person') }}" placeholder=>
                            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="2" placeholder=>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="alert alert-info mt-3" style="font-size:13px;">
                        <i class="fas fa-info-circle me-2"></i>
                        A system account will be created for this hospital with a default password of <strong>password</strong>.
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Save Hospital</button>
                        <a href="{{ route('hospitals.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
