@extends('layouts.app')
@section('title','Blood Test Details')
@section('page-title','Blood Testing')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-tests.index') }}">Blood Tests</a></li>
    <li class="breadcrumb-item active">Test #{{ $blood_test->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Test #{{ $blood_test->id }}</h2>
        <p class="page-sub">Full screening result details</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('blood-tests.edit', $blood_test->id) }}" class="btn btn-blood">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

@php
    $allClear = $blood_test->hiv_result==='Negative' && $blood_test->hbv_result==='Negative'
             && $blood_test->hcv_result==='Negative' && $blood_test->syphilis_result==='Negative';
@endphp

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2 text-danger"></i>Collection Info</div>
            <div class="card-body">
                @if($blood_test->bloodCollection)
                <table class="table table-sm mb-0">
                    <tr>
                        <th style="color:#94a3b8;font-size:12px;width:40%;">Collection #</th>
                        <td>
                            <a href="{{ route('blood-collections.show', $blood_test->blood_collection_id) }}" class="text-danger fw-600">
                                #{{ $blood_test->blood_collection_id }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th style="color:#94a3b8;font-size:12px;">Donor</th>
                        <td>
                            @if($blood_test->bloodCollection->donor)
                                <a href="{{ route('donors.show',$blood_test->bloodCollection->donor->id) }}" class="text-danger fw-600">
                                    {{ $blood_test->bloodCollection->donor->full_name }}
                                </a>
                            @else N/A @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="color:#94a3b8;font-size:12px;">Blood Group</th>
                        <td><span class="blood-group-pill">{{ $blood_test->bloodCollection->blood_group }}</span></td>
                    </tr>
                    <tr>
                        <th style="color:#94a3b8;font-size:12px;">Donation Date</th>
                        <td>{{ $blood_test->bloodCollection->donation_date ? $blood_test->bloodCollection->donation_date->format('d M Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th style="color:#94a3b8;font-size:12px;">Quantity</th>
                        <td>{{ number_format($blood_test->bloodCollection->quantity) }} ml</td>
                    </tr>
                </table>
                @else
                <p class="text-muted">Collection data unavailable.</p>
                @endif
                <div class="mt-3 p-3 rounded-2 text-center" style="background:{{ $allClear ? '#f0fdf4' : '#fef2f2' }};">
                    <div class="fw-800 fs-5" style="color:{{ $allClear ? '#16a34a' : '#dc2626' }};">
                        <i class="fas fa-{{ $allClear ? 'check-circle' : 'times-circle' }} me-2"></i>
                        {{ $allClear ? 'SAFE' : 'UNSAFE' }}
                    </div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Overall Screening Result</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-vial me-2 text-danger"></i>Test Results
                <span class="ms-2 text-muted" style="font-size:12px;">
                    Tested on {{ $blood_test->test_date ? $blood_test->test_date->format('d M Y') : '—' }}
                </span>
            </div>
            <div class="card-body">
                @foreach(['hiv_result'=>'HIV','hbv_result'=>'HBV (Hepatitis B)','hcv_result'=>'HCV (Hepatitis C)','syphilis_result'=>'Syphilis'] as $field => $label)
                <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded-2"
                    style="background:{{ $blood_test->$field==='Negative' ? '#f0fdf4' : '#fef2f2' }};border:1.5px solid {{ $blood_test->$field==='Negative' ? '#bbf7d0' : '#fecaca' }};">
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#1e293b;">{{ $label }}</div>
                        <div style="font-size:12px;color:#94a3b8;">Screening marker</div>
                    </div>
                    <span class="badge-status {{ $blood_test->$field==='Negative' ? 'badge-safe' : 'badge-unsafe' }}" style="font-size:13px;padding:6px 16px;">
                        <i class="fas fa-{{ $blood_test->$field==='Negative' ? 'check' : 'times' }} me-1"></i>
                        {{ $blood_test->$field }}
                    </span>
                </div>
                @endforeach

                @if($blood_test->notes)
                <div class="p-3 rounded-2 mt-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div class="fw-600 mb-1" style="font-size:12.5px;color:#374151;"><i class="fas fa-sticky-note me-1 text-muted"></i>Notes</div>
                    <p class="mb-0" style="font-size:13.5px;color:#475569;">{{ $blood_test->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
