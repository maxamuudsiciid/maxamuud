@extends('layouts.app')
@section('title', 'Add Donor')
@section('page-title', 'Add Donor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Add New Donor</h2>
        <p class="page-sub">Register a new blood donor in the system</p>
    </div>
    <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-user-plus me-2 text-danger"></i>Donor Information</div>
            <div class="card-body">
                <form action="{{ route('donors.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                                value="{{ old('full_name') }}" placeholder= >
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder=>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" >
                                <option value=""></option>
                                <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}" >
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" >
                                <option value=""></option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder= >
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Donation Date</label>
                            <input type="date" name="last_donation_date" class="form-control @error('last_donation_date') is-invalid @enderror"
                                value="{{ old('last_donation_date') }}">
                            @error('last_donation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="2" placeholder= >{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0" style="font-size:13px;">
                        <i class="fas fa-info-circle me-2"></i>
                        A system account will be automatically created for this donor with the email above and a default password of <strong>password</strong>. Advise the donor to change their password after first login.
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Save Donor</button>
                        <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
