@extends('layouts.app')
@section('title','Blood Tests Dashboard')
@section('page-title','Blood Tests Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-tests.index') }}">Blood Tests</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Tests Dashboard</h2>
        <p class="page-sub">Overview of screening statistics and recent reports</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-list me-2"></i>View All Reports
        </a>
        <a href="{{ route('blood-tests.create') }}" class="btn btn-blood">
            <i class="fas fa-plus me-2"></i>Record Test
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card h-100">
            <div class="stat-icon" style="background:#f1f5f9;"><i class="fas fa-microscope" style="color:#64748b;"></i></div>
            <div>
                <div class="stat-label">Total Blood Tests</div>
                <div class="stat-value">{{ $totalTests }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card h-100">
            <div class="stat-icon" style="background:#fef2f2;"><i class="fas fa-vial-virus" style="color:#dc2626;"></i></div>
            <div>
                <div class="stat-label">Positive Results</div>
                <div class="stat-value text-danger">{{ $positiveResults }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card h-100">
            <div class="stat-icon" style="background:#f0fdf4;"><i class="fas fa-shield-virus" style="color:#16a34a;"></i></div>
            <div>
                <div class="stat-label">Negative Results</div>
                <div class="stat-value text-success">{{ $negativeResults }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card h-100">
            <div class="stat-icon" style="background:#eff6ff;"><i class="fas fa-clipboard-check" style="color:#2563eb;"></i></div>
            <div>
                <div class="stat-label">Recent Reports</div>
                <div class="stat-value">{{ $recentReports->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div><i class="fas fa-clock me-2 text-danger"></i>Recent Blood Tests</div>
        <a href="{{ route('blood-tests.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>Test Date</th>
                        <th>Overall Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $i => $test)
                    @php
                        $allClear = $test->hiv_result==='Negative' && $test->hbv_result==='Negative'
                                 && $test->hcv_result==='Negative' && $test->syphilis_result==='Negative';
                    @endphp
                    <tr>
                        <td>{{ $test->id }}</td>
                        <td>
                            @if($test->bloodCollection && $test->bloodCollection->donor)
                                <strong>{{ $test->bloodCollection->donor->full_name }}</strong>
                            @else N/A @endif
                        </td>
                        <td>
                            @if($test->bloodCollection)
                                <span class="blood-group-pill">{{ $test->bloodCollection->blood_group }}</span>
                            @else — @endif
                        </td>
                        <td>{{ $test->test_date ? $test->test_date->format('d M Y') : '—' }}</td>
                        <td>
                            <span class="badge-status {{ $allClear ? 'badge-safe' : 'badge-unsafe' }}">
                                {{ $allClear ? 'Safe' : 'Unsafe' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('blood-tests.show', $test->id) }}" class="btn btn-sm btn-outline-secondary py-1" style="font-size:12px;">View Report</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state py-4">
                                <i class="fas fa-microscope"></i>
                                <p>No recent reports.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
