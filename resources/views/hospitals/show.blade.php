@extends('layouts.app')
@section('title', $hospital->hospital_name)
@section('page-title','Hospitals')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('hospitals.index') }}">Hospitals</a></li>
    <li class="breadcrumb-item active">{{ $hospital->hospital_name }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">{{ $hospital->hospital_name }}</h2>
        <p class="page-sub">Hospital profile and request history</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hospitals.edit', $hospital->id) }}" class="btn btn-blood">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('hospitals.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                @php
                    $canViewImage = in_array(Auth::user()->role, ['admin', 'staff']) || (Auth::user()->role === 'hospital' && Auth::user()->hospital && Auth::user()->hospital->id === $hospital->id);
                @endphp
                @if($hospital->user && $hospital->user->image && $canViewImage)
                    <img src="{{ asset('storage/profiles/'.$hospital->user->image) }}" alt="Profile Photo" style="width:72px;height:72px;border-radius:18px;object-fit:cover;margin:0 auto 16px;display:block;cursor:pointer;" onclick="window.open(this.src, '_blank')">
                @else
                    <div style="width:72px;height:72px;background:linear-gradient(135deg,#2563eb,#1e40af);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;margin:0 auto 16px;">
                        <i class="fas fa-hospital"></i>
                    </div>
                @endif
                <h5 class="fw-700 mb-1">{{ $hospital->hospital_name }}</h5>
                <p class="text-muted mb-3" style="font-size:13px;">{{ $hospital->email }}</p>
            </div>
            <div class="card-body border-top pt-3">
                <table class="table table-sm mb-0">
                    <tr><th style="font-size:12px;color:#94a3b8;">Contact Person</th><td>{{ $hospital->contact_person }}</td></tr>
                    <tr><th style="font-size:12px;color:#94a3b8;">Phone</th><td>{{ $hospital->phone }}</td></tr>
                    <tr><th style="font-size:12px;color:#94a3b8;">Address</th><td>{{ $hospital->address }}</td></tr>
                    <tr><th style="font-size:12px;color:#94a3b8;">Registered</th><td>{{ $hospital->created_at->format('d M Y') }}</td></tr>
                    <tr><th style="font-size:12px;color:#94a3b8;">Total Requests</th><td><strong>{{ $hospital->bloodRequests->count() }}</strong></td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-clipboard-list me-2 text-danger"></i>Blood Requests History</div>
                <a href="{{ route('blood-requests.create') }}" class="btn btn-sm btn-blood">
                    <i class="fas fa-plus me-1"></i>New Request
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Blood Group</th>
                            <th>Qty (ml)</th>
                            <th>Urgency</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hospital->bloodRequests()->latest()->get() as $i => $req)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $req->patient_name }}</td>
                            <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                            <td>{{ number_format($req->quantity) }}</td>
                            <td><span class="badge-status badge-{{ strtolower($req->urgency_level) }}">{{ $req->urgency_level }}</span></td>
                            <td>{{ $req->request_date ? $req->request_date->format('d M Y') : '—' }}</td>
                            <td><span class="badge-status badge-{{ strtolower($req->status) }}">{{ $req->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
