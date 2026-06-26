@extends('layouts.app')
@section('title', $donor->full_name)
@section('page-title', 'Donor Profile')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('donors.index') }}">Donors</a></li>
    <li class="breadcrumb-item active">{{ $donor->full_name }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">{{ $donor->full_name }}</h2>
        <p class="page-sub">Donor Profile & Donation History</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array(Auth::user()->role,['admin','staff']) || (Auth::user()->role==='donor' && Auth::user()->donor && Auth::user()->donor->id===$donor->id))
        <a href="{{ route('donors.edit', $donor->id) }}" class="btn btn-blood">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </a>
        @endif
        <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center pt-4">
                @php
                    $canViewImage = in_array(Auth::user()->role, ['admin', 'staff']) || (Auth::user()->role === 'donor' && Auth::user()->donor && Auth::user()->donor->id === $donor->id);
                @endphp
                @if($donor->user && $donor->user->image && $canViewImage)
                    <img src="{{ asset('storage/profiles/'.$donor->user->image) }}" alt="Profile Photo" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;cursor:pointer;" onclick="window.open(this.src, '_blank')">
                @else
                    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#7f1d1d);display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px;font-weight:700;margin:0 auto 16px;">
                        {{ strtoupper(substr($donor->full_name,0,1)) }}
                    </div>
                @endif
                <h5 class="fw-700 mb-1" style="color:#1e293b;">{{ $donor->full_name }}</h5>
                <p class="text-muted mb-2" style="font-size:13px;">{{ $donor->email }}</p>
                <span class="blood-group-pill fs-5 mb-3 d-inline-block">{{ $donor->blood_group }}</span><br>
                <span class="badge-status badge-{{ strtolower($donor->status) }}">{{ $donor->status }}</span>
            </div>
            <div class="card-body border-top pt-3">
                <table class="table table-sm mb-0">
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Gender</th>
                        <td style="font-size:13.5px;">{{ $donor->gender }}</td>
                    </tr>
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Date of Birth</th>
                        <td style="font-size:13.5px;">{{ $donor->date_of_birth ? $donor->date_of_birth->format('d M Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Phone</th>
                        <td style="font-size:13.5px;">{{ $donor->phone }}</td>
                    </tr>
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Last Donation</th>
                        <td style="font-size:13.5px;">
                            {{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : '—' }}
                        </td>
                    </tr>
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Address</th>
                        <td style="font-size:13.5px;">{{ $donor->address }}</td>
                    </tr>
                    <tr>
                        <th style="font-size:12px;color:#94a3b8;font-weight:600;">Registered</th>
                        <td style="font-size:13.5px;">{{ $donor->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if(in_array(Auth::user()->role,['admin','staff']))
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="fw-700 mb-3" style="font-size:13px;color:#1e293b;">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('blood-collections.create') }}?donor_id={{ $donor->id }}" class="btn btn-blood btn-sm">
                        <i class="fas fa-syringe me-2"></i>Record Donation
                    </a>
                    <form action="{{ route('donors.destroy', $donor->id) }}" method="POST" class="confirm-delete">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm w-100" style="background:#fee2e2;color:#dc2626;border:none;">
                            <i class="fas fa-trash me-2"></i>Delete Donor
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Donation History --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-syringe me-2 text-danger"></i>Donation History</div>
                <span class="badge" style="background:#fee2e2;color:#991b1b;">
                    {{ $donor->bloodCollections->count() }} total
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Donation Date</th>
                            <th>Qty (ml)</th>
                            <th>Expiry Date</th>
                            <th>Screening</th>
                            <th>Test</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donor->bloodCollections()->latest('donation_date')->get() as $i => $col)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $col->donation_date ? $col->donation_date->format('d M Y') : '—' }}</td>
                            <td>{{ number_format($col->quantity) }}</td>
                            <td>
                                @if($col->expiry_date)
                                    <span class="{{ $col->expiry_date->isPast() ? 'text-danger' : ($col->expiry_date->diffInDays(now()) < 7 ? 'text-warning' : 'text-success') }}">
                                        {{ $col->expiry_date->format('d M Y') }}
                                    </span>
                                @else —
                                @endif
                            </td>
                            <td><span class="badge-status badge-{{ strtolower($col->screening_result) }}">{{ $col->screening_result }}</span></td>
                            <td>
                                @if($col->bloodTest)
                                    <a href="{{ route('blood-tests.show', $col->bloodTest->id) }}" class="badge" style="background:#dbeafe;color:#1e40af;text-decoration:none;">
                                        <i class="fas fa-microscope me-1"></i>View
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:12px;">Not tested</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-syringe"></i>
                                    <p>No donation records found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
