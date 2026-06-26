@extends('layouts.app')
@section('title','Book Appointment')
@section('page-title','Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item active">Book</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Book Appointment</h2>
        <p class="page-sub">Schedule your blood donation</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-plus me-2 text-danger"></i>Select Date and Time</div>
            <div class="card-body">
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    
                    @if(in_array(Auth::user()->role, ['admin','staff']))
                        <div class="mb-3">
                            <label class="form-label">Select Donor <span class="text-danger">*</span></label>
                            <select name="donor_id" class="form-select @error('donor_id') is-invalid @enderror" required>
                                <option value=""></option>
                                @foreach(\App\Models\Donor::orderBy('full_name')->get() as $donor)
                                    <option value="{{ $donor->id }}" {{ old('donor_id') == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->full_name }} ({{ $donor->blood_group }})
                                    </option>
                                @endforeach
                            </select>
                            @error('donor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror"
                                value="{{ old('appointment_date') }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                            @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time <span class="text-danger">*</span></label>
                            <select name="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                <option value=""></option>
                                @php
                                    $start = \Carbon\Carbon::createFromTime(9, 0); // 09:00 AM
                                    $end = \Carbon\Carbon::createFromTime(17, 0);   // 05:00 PM
                                @endphp
                                @while($start->lessThanOrEqualTo($end))
                                    @php $timeVal = $start->format('H:i'); @endphp
                                    <option value="{{ $timeVal }}" {{ old('appointment_time') == $timeVal ? 'selected' : '' }}>
                                        {{ $start->format('h:i A') }}
                                    </option>
                                    @php $start->addMinutes(30); @endphp
                                @endwhile
                            </select>
                            @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-check-circle me-2"></i>Confirm Booking</button>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
