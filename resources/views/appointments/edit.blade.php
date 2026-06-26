@extends('layouts.app')
@section('title','Reschedule Appointment')
@section('page-title','Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item active">Reschedule</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Reschedule Appointment</h2>
        <p class="page-sub">Change the date and time of the booking</p>
    </div>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Update Date and Time</div>
            <div class="card-body">
                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror"
                                value="{{ old('appointment_date', $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d') : '') }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                            @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time <span class="text-danger">*</span></label>
                            <select name="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                <option value=""></option>
                                @php
                                    $start = \Carbon\Carbon::createFromTime(9, 0); // 09:00 AM
                                    $end = \Carbon\Carbon::createFromTime(17, 0);   // 05:00 PM
                                    $savedTime = \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i');
                                @endphp
                                @while($start->lessThanOrEqualTo($end))
                                    @php $timeVal = $start->format('H:i'); @endphp
                                    <option value="{{ $timeVal }}" {{ old('appointment_time', $savedTime) == $timeVal ? 'selected' : '' }}>
                                        {{ $start->format('h:i A') }}
                                    </option>
                                    @php $start->addMinutes(30); @endphp
                                @endwhile
                            </select>
                            @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    @if(Auth::user()->role === 'donor')
                        <div class="alert alert-warning mt-3 mb-0" style="font-size:13px;">
                            <i class="fas fa-info-circle me-1"></i> Rescheduling will revert the appointment status back to <strong>Pending</strong>.
                        </div>
                    @endif
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update Booking</button>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
