@extends('layouts.app')
@section('title','Submit Blood Request')
@section('page-title','Blood Requests')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-requests.index') }}">Blood Requests</a></li>
    <li class="breadcrumb-item active">New Request</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Submit Blood Request</h2>
        <p class="page-sub">Request blood units from the blood bank</p>
    </div>
    <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle me-2 text-danger"></i>Request Details</div>
            <div class="card-body">
                <form action="{{ route('blood-requests.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Hospital <span class="text-danger">*</span></label>
                            @if(auth()->user()->role === 'hospital')
                                <input type="hidden" name="hospital_id" value="{{ $hospitals->first()->id ?? '' }}">
                                <input type="text" class="form-control" value="{{ $hospitals->first()->hospital_name ?? '' }}" readonly>
                            @else
                                <select name="hospital_id" class="form-select @error('hospital_id') is-invalid @enderror" required>
                                    <option value=""></option>
                                    @foreach($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}" {{ old('hospital_id')==$hospital->id?'selected':'' }}>
                                            {{ $hospital->hospital_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hospital_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control @error('patient_name') is-invalid @enderror"
                                value="{{ old('patient_name') }}" placeholder=>
                            @error('patient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" required>
                                <option value=""></option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity (ml) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity') }}" min="1" placeholder=>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Urgency Level <span class="text-danger">*</span></label>
                            <select name="urgency_level" class="form-select @error('urgency_level') is-invalid @enderror" required>
                                <option value="Normal" {{ old('urgency_level')=='Normal'?'selected':'' }}>Normal</option>
                                <option value="Urgent" {{ old('urgency_level')=='Urgent'?'selected':'' }}>Urgent</option>
                                <option value="Emergency" {{ old('urgency_level')=='Emergency'?'selected':'' }}>Emergency</option>
                            </select>
                            @error('urgency_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Request Date <span class="text-danger">*</span></label>
                            <input type="date" name="request_date" class="form-control @error('request_date') is-invalid @enderror"
                                value="{{ old('request_date', date('Y-m-d')) }}" required>
                            @error('request_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-paper-plane me-2"></i>Submit Request</button>
                        <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
