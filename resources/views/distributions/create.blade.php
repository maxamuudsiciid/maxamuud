@extends('layouts.app')
@section('title','Create Distribution')
@section('page-title','Blood Distribution')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('distributions.index') }}">Distributions</a></li>
    <li class="breadcrumb-item active">New Distribution</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">New Blood Distribution</h2>
        <p class="page-sub">Distribute blood to a hospital for an approved request</p>
    </div>
    <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-truck-medical me-2 text-danger"></i>Distribution Details</div>
            <div class="card-body">
                <form action="{{ route('distributions.store') }}" method="POST">
                    @csrf
                    @if($bloodRequest)
                    <div class="p-3 mb-4 rounded-2" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                        <div class="fw-700 mb-1" style="font-size:13.5px;color:#166534;">
                            <i class="fas fa-check-circle me-2"></i>Pre-selected Request
                        </div>
                        <table class="table table-sm mb-0" style="font-size:13px;">
                            <tr><th style="width:35%;color:#94a3b8;">Hospital</th><td>{{ $bloodRequest->hospital->hospital_name ?? 'N/A' }}</td></tr>
                            <tr><th style="color:#94a3b8;">Patient</th><td>{{ $bloodRequest->patient_name }}</td></tr>
                            <tr><th style="color:#94a3b8;">Blood Group</th><td><span class="blood-group-pill">{{ $bloodRequest->blood_group }}</span></td></tr>
                            <tr><th style="color:#94a3b8;">Quantity</th><td>{{ number_format($bloodRequest->quantity) }} ml</td></tr>
                            <tr><th style="color:#94a3b8;">Urgency</th><td><span class="badge-status badge-{{ strtolower($bloodRequest->urgency_level) }}">{{ $bloodRequest->urgency_level }}</span></td></tr>
                        </table>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Approved Blood Request <span class="text-danger">*</span></label>
                        <select name="blood_request_id" class="form-select @error('blood_request_id') is-invalid @enderror" required>
                            <option value="">Select approved request...</option>
                            @foreach($requests as $req)
                                <option value="{{ $req->id }}" {{ (old('blood_request_id', $bloodRequest?->id)==$req->id)?'selected':'' }}>
                                    #{{ $req->id }} — {{ $req->hospital->hospital_name ?? 'N/A' }}
                                    ({{ $req->blood_group }}, {{ number_format($req->quantity) }}ml, {{ $req->urgency_level }})
                                </option>
                            @endforeach
                        </select>
                        @error('blood_request_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($requests->isEmpty())
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                No approved requests available. <a href="{{ route('blood-requests.index') }}" class="text-danger">Approve a request first</a>.
                            </small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Distribution Date <span class="text-danger">*</span></label>
                        <input type="date" name="distribution_date" class="form-control @error('distribution_date') is-invalid @enderror"
                            value="{{ old('distribution_date', date('Y-m-d')) }}" required>
                        @error('distribution_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-warning" style="font-size:13px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action will <strong>deduct the requested quantity from inventory</strong> and mark the request as <strong>Fulfilled</strong>. This cannot be undone.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-blood" {{ $requests->isEmpty() ? 'disabled' : '' }}>
                            <i class="fas fa-truck-medical me-2"></i>Confirm Distribution
                        </button>
                        <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
