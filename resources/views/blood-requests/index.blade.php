@extends('layouts.app')
@section('title','Blood Requests')
@section('page-title','Blood Requests')
@section('breadcrumb')
    <li class="breadcrumb-item active">Blood Requests</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Requests</h2>
        <p class="page-sub">Manage blood requests from hospitals</p>
    </div>
    <a href="{{ route('blood-requests.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>New Request
    </a>
</div>

{{-- Stats Row --}}
@if(in_array(Auth::user()->role,['admin','staff']))
<div class="row g-3 mb-4">
    @php
        $allReqs = \App\Models\BloodRequest::selectRaw('status, count(*) as cnt')->groupBy('status')->pluck('cnt','status');
    @endphp
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;"><i class="fas fa-clock" style="color:#d97706;"></i></div>
            <div><div class="stat-label">Pending</div><div class="stat-value">{{ $allReqs['Pending'] ?? 0 }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;"><i class="fas fa-check" style="color:#16a34a;"></i></div>
            <div><div class="stat-label">Approved</div><div class="stat-value">{{ $allReqs['Approved'] ?? 0 }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;"><i class="fas fa-truck-medical" style="color:#2563eb;"></i></div>
            <div><div class="stat-label">Fulfilled</div><div class="stat-value">{{ $allReqs['Fulfilled'] ?? 0 }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;"><i class="fas fa-times-circle" style="color:#dc2626;"></i></div>
            <div><div class="stat-label">Rejected</div><div class="stat-value">{{ $allReqs['Rejected'] ?? 0 }}</div></div>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header"><i class="fas fa-clipboard-list me-2 text-danger"></i>Blood Requests</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hospital</th>
                        <th>Patient</th>
                        <th>Blood Group</th>
                        <th>Qty (ml)</th>
                        <th>Urgency</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $i => $req)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $req->hospital->hospital_name ?? 'N/A' }}</td>
                        <td>{{ $req->patient_name }}</td>
                        <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                        <td>{{ number_format($req->quantity) }}</td>
                        <td>
                            <span class="badge-status badge-{{ strtolower($req->urgency_level) }}">
                                {{ $req->urgency_level }}
                            </span>
                        </td>
                        <td>{{ $req->request_date ? $req->request_date->format('d M Y') : '—' }}</td>
                        <td>
                            @if(in_array(Auth::user()->role,['admin','staff']) && $req->status === 'Pending')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('blood-requests.updateStatus', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button class="btn btn-sm" style="background:#dcfce7;color:#166534;border:none;font-size:11px;">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('blood-requests.updateStatus', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;font-size:11px;">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge-status badge-{{ strtolower($req->status) }}">{{ $req->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if(in_array(Auth::user()->role, ['admin','staff']) || (Auth::user()->role === 'hospital' && Auth::user()->hospital && Auth::user()->hospital->id === $req->hospital_id))
                                <a href="{{ route('blood-requests.edit', $req->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(in_array(Auth::user()->role,['admin','staff']) && $req->status === 'Approved')
                                <a href="{{ route('distributions.create') }}?request_id={{ $req->id }}" class="btn btn-sm" style="background:#f3e8ff;color:#7c3aed;" title="Distribute">
                                    <i class="fas fa-truck-medical"></i>
                                </a>
                                @endif
                                @if(in_array(Auth::user()->role,['admin','staff']) || (Auth::user()->role === 'hospital' && Auth::user()->hospital && Auth::user()->hospital->id === $req->hospital_id))
                                <form action="{{ route('blood-requests.destroy', $req->id) }}" method="POST" class="confirm-delete">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <p>No blood requests found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($requests,'hasPages') && $requests->hasPages())
    <div class="card-body border-top py-3">{{ $requests->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
