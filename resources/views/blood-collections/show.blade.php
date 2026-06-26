@extends('layouts.app')
@section('title','Collection Details')
@section('page-title','Collection Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-collections.index') }}">Blood Collections</a></li>
    <li class="breadcrumb-item active">#{{ $collection->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Collection #{{ $collection->id }}</h2>
        <p class="page-sub">Blood collection record details</p>
    </div>
    <div class="d-flex gap-2">
        @if(in_array(Auth::user()->role,['admin','staff']))
        <a href="{{ route('blood-collections.edit', $collection->id) }}" class="btn btn-blood">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        @endif
        <a href="{{ route('blood-collections.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-syringe me-2 text-danger"></i>Collection Info</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th style="color:#94a3b8;font-size:12px;width:40%;">Collection ID</th><td>#{{ $collection->id }}</td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Donor</th>
                        <td>
                            @if($collection->donor)
                                <a href="{{ route('donors.show', $collection->donor->id) }}" class="text-danger fw-600">{{ $collection->donor->full_name }}</a>
                            @else N/A @endif
                        </td>
                    </tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Blood Group</th><td><span class="blood-group-pill">{{ $collection->blood_group }}</span></td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Quantity</th><td><strong>{{ number_format($collection->quantity) }} ml</strong></td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Donation Date</th><td>{{ $collection->donation_date ? $collection->donation_date->format('d M Y') : '—' }}</td></tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Expiry Date</th>
                        <td>
                            @if($collection->expiry_date)
                                @php $expired=$collection->expiry_date->isPast(); @endphp
                                <span class="{{ $expired ? 'text-danger fw-600' : 'text-success' }}">
                                    {{ $collection->expiry_date->format('d M Y') }}
                                    @if($expired) <span class="badge bg-danger ms-1">Expired</span> @endif
                                </span>
                            @else — @endif
                        </td>
                    </tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Screening</th>
                        <td><span class="badge-status badge-{{ strtolower($collection->screening_result) }}">{{ $collection->screening_result }}</span></td>
                    </tr>
                    <tr><th style="color:#94a3b8;font-size:12px;">Recorded</th><td>{{ $collection->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-microscope me-2 text-danger"></i>Blood Test Results</div>
            <div class="card-body">
                @if($collection->bloodTest)
                    @php $test = $collection->bloodTest; @endphp
                    <div class="mb-3">
                        <span class="text-muted" style="font-size:12px;">Test Date</span>
                        <div class="fw-600">{{ $test->test_date ? $test->test_date->format('d M Y') : '—' }}</div>
                    </div>
                    @foreach(['hiv_result'=>'HIV','hbv_result'=>'HBV (Hepatitis B)','hcv_result'=>'HCV (Hepatitis C)','syphilis_result'=>'Syphilis'] as $field => $label)
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-2"
                        style="background:{{ $test->$field === 'Negative' ? '#f0fdf4' : '#fef2f2' }}">
                        <span style="font-size:13px;font-weight:600;color:#374151;">{{ $label }}</span>
                        <span class="badge-status badge-{{ $test->$field === 'Negative' ? 'safe' : 'unsafe' }}">{{ $test->$field }}</span>
                    </div>
                    @endforeach
                    @if($test->notes)
                    <div class="mt-3 p-2 rounded-2" style="background:#f8fafc;font-size:13px;">
                        <strong>Notes:</strong> {{ $test->notes }}
                    </div>
                    @endif
                    <a href="{{ route('blood-tests.show', $test->id) }}" class="btn btn-sm btn-blood-outline mt-3">
                        <i class="fas fa-external-link-alt me-1"></i>View Full Test
                    </a>
                @else
                    <div class="empty-state py-3">
                        <i class="fas fa-microscope"></i>
                        <p>No blood test recorded for this collection.</p>
                        @if(in_array(Auth::user()->role,['admin','staff']))
                        <a href="{{ route('blood-tests.create') }}" class="btn btn-blood btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Test
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
