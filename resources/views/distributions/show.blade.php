@extends('layouts.app')
@section('title','Distribution Details')
@section('page-title','Blood Distribution')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('distributions.index') }}">Distributions</a></li>
    <li class="breadcrumb-item active">#{{ $distribution->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Distribution #{{ $distribution->id }}</h2>
        <p class="page-sub">Blood distribution record details</p>
    </div>
    <a href="{{ route('distributions.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-truck-medical me-2 text-danger"></i>Distribution Info</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th style="color:#94a3b8;font-size:12px;width:40%;">Distribution #</th><td>#{{ $distribution->id }}</td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Hospital</th><td><strong>{{ $distribution->hospital->hospital_name ?? 'N/A' }}</strong></td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Blood Group</th><td><span class="blood-group-pill">{{ $distribution->blood_group }}</span></td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Quantity</th><td><strong>{{ number_format($distribution->quantity) }} ml</strong></td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Distribution Date</th><td>{{ $distribution->distribution_date ? $distribution->distribution_date->format('d M Y') : '—' }}</td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Approved By</th><td>{{ $distribution->approvedBy->name ?? '—' }}</td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Created At</th><td>{{ $distribution->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-clipboard-list me-2 text-danger"></i>Linked Blood Request</div>
            <div class="card-body">
                @if($distribution->bloodRequest)
                    @php $req = $distribution->bloodRequest; @endphp
                    <table class="table table-sm mb-0">
                        <tr><th style="color:#94a3b8;font-size:12px;width:40%;">Request #</th><td>#{{ $req->id }}</td></tr>
                        <tr><th style="color:#94a3b8;font-size:12px;">Patient</th><td>{{ $req->patient_name }}</td></tr>
                        <tr><th style="color:#94a3b8;font-size:12px;">Blood Group</th><td><span class="blood-group-pill">{{ $req->blood_group }}</span></td></tr>
                        <tr><th style="color:#94a3b8;font-size:12px;">Qty Requested</th><td>{{ number_format($req->quantity) }} ml</td></tr>
                        <tr><th style="color:#94a3b8;font-size:12px;">Urgency</th><td><span class="badge-status badge-{{ strtolower($req->urgency_level) }}">{{ $req->urgency_level }}</span></td></tr>
                        <tr><th style="color:#94a3b8;font-size:12px;">Status</th><td><span class="badge-status badge-{{ strtolower($req->status) }}">{{ $req->status }}</span></td></tr>
                    </table>
                @else
                    <div class="empty-state py-3"><i class="fas fa-clipboard-list"></i><p>No linked request.</p></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
