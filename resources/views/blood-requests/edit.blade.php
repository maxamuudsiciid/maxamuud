@extends('layouts.app')
@section('title','Edit Blood Request')
@section('page-title','Edit Blood Request')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-requests.index') }}">Blood Requests</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Blood Request</h2>
        <p class="page-sub">Update request #{{ $req->id }}</p>
    </div>
    <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Request #{{ $req->id }}</div>
            <div class="card-body">
                <form action="{{ route('blood-requests.update', $req->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Hospital <span class="text-danger">*</span></label>
                            @if(auth()->user()->role === 'hospital')
                                <input type="hidden" name="hospital_id" value="{{ $hospitals->first()->id ?? '' }}">
                                <input type="text" class="form-control" value="{{ $hospitals->first()->hospital_name ?? '' }}" readonly>
                            @else
                                <select name="hospital_id" class="form-select" required>
                                    @foreach($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}" {{ old('hospital_id',$req->hospital_id)==$hospital->id?'selected':'' }}>
                                            {{ $hospital->hospital_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control @error('patient_name') is-invalid @enderror"
                                value="{{ old('patient_name',$req->patient_name) }}" required>
                            @error('patient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select" required>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group',$req->blood_group)==$bg?'selected':'' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantity (ml) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity',$req->quantity) }}" min="1" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urgency Level <span class="text-danger">*</span></label>
                            <select name="urgency_level" class="form-select" required>
                                @foreach(['Normal','Urgent','Emergency'] as $u)
                                    <option value="{{ $u }}" {{ old('urgency_level',$req->urgency_level)==$u?'selected':'' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Request Date <span class="text-danger">*</span></label>
                            <input type="date" name="request_date" class="form-control"
                                value="{{ old('request_date', $req->request_date ? $req->request_date->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            @if(in_array(auth()->user()->role, ['admin','staff']))
                            <select name="status" class="form-select" required>
                                @foreach(['Pending','Approved','Rejected','Fulfilled'] as $s)
                                    <option value="{{ $s }}" {{ old('status',$req->status)==$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @else
                                <input type="hidden" name="status" value="{{ $req->status }}">
                                <input type="text" class="form-control" value="{{ $req->status }}" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update Request</button>
                        <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
