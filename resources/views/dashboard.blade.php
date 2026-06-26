@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Overview</li>
@endsection

@section('content')

{{-- ============================================================ ADMIN / STAFF DASHBOARD --}}
@if(in_array(Auth::user()->role, ['admin', 'staff']))

{{-- Stats Row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;">
                <i class="fas fa-users" style="color:#dc2626;"></i>
            </div>
            <div>
                <div class="stat-label">Total Donors</div>
                <div class="stat-value">{{ number_format($totalDonors ?? 0) }}</div>
                <div class="stat-meta">
                    <span class="up"><i class="fas fa-circle fa-xs me-1"></i>{{ $activeDonors ?? 0 }} active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">
                <i class="fas fa-droplet" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-label">Blood Units (ml)</div>
                <div class="stat-value">{{ number_format($totalBloodUnits ?? 0) }}</div>
                <div class="stat-meta">Total inventory stock</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="fas fa-clipboard-list" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-label">Pending Requests</div>
                <div class="stat-value">{{ number_format($pendingRequests ?? 0) }}</div>
                <div class="stat-meta">
                    <span class="up">{{ $approvedRequests ?? 0 }} approved</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="fas fa-hospital" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-label">Hospitals</div>
                <div class="stat-value">{{ number_format($totalHospitals ?? 0) }}</div>
                <div class="stat-meta">Registered facilities</div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e8ff;">
                <i class="fas fa-users-gear" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
                <div class="stat-meta">System accounts</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3;">
                <i class="fas fa-truck-medical" style="color:#be185d;"></i>
            </div>
            <div>
                <div class="stat-label">Distributions</div>
                <div class="stat-value">{{ number_format($totalDistributions ?? 0) }}</div>
                <div class="stat-meta">Total fulfilled</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
                <i class="fas fa-triangle-exclamation" style="color:#ea580c;"></i>
            </div>
            <div>
                <div class="stat-label">Expiring Soon</div>
                <div class="stat-value">{{ number_format($expiringUnits ?? 0) }}</div>
                <div class="stat-meta"><span class="down">Within 7 days</span></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">
                <i class="fas fa-check-circle" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-label">Active Donors</div>
                <div class="stat-value">{{ number_format($activeDonors ?? 0) }}</div>
                <div class="stat-meta">
                    @if(($totalDonors ?? 0) > 0)
                        {{ round(($activeDonors / $totalDonors) * 100, 1) }}% of total
                    @else
                        0% of total
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    {{-- Monthly Collections Bar Chart --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-chart-bar me-2 text-danger"></i>Monthly Blood Collections</div>
                <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:11px;">Last 6 Months</span>
            </div>
            <div class="card-body">
                @if(isset($chartLabels) && $chartLabels !== '[]')
                    <canvas id="monthlyChart" height="100"></canvas>
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <p>No collection data available yet. Add blood collections to see the chart.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Blood Group Donut Chart --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2 text-danger"></i>Blood Group Stock
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                @if(isset($bloodGroupsData) && $bloodGroupsData->isNotEmpty() && $bloodGroupsData->sum('quantity') > 0)
                    <canvas id="donutChart" style="max-height:200px;"></canvas>
                    <div class="mt-3 w-100">
                        @foreach($bloodGroupsData as $inv)
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:12.5px;">
                            <span><span class="blood-group-pill me-1">{{ $inv->blood_group }}</span></span>
                            <span class="fw-600 text-muted">{{ number_format($inv->quantity) }} ml</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-pie"></i>
                        <p>No inventory data available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Blood Inventory Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-box-open me-2 text-danger"></i>Blood Inventory by Group</div>
                <a href="{{ route('blood-inventory.index') }}" class="btn btn-sm btn-blood">
                    <i class="fas fa-arrow-right me-1"></i> Full Inventory
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($bloodGroupsData ?? [] as $inv)
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="p-3 rounded-3 text-center h-100"
                            style="background:{{ $inv->quantity < 500 ? '#fef2f2;border:1.5px solid #fecaca;' : ($inv->quantity < 1000 ? '#fffbeb;border:1.5px solid #fde68a;' : '#f0fdf4;border:1.5px solid #bbf7d0;') }}">
                            <div class="fs-3 fw-800" style="color:{{ $inv->quantity < 500 ? '#dc2626' : ($inv->quantity < 1000 ? '#d97706' : '#16a34a') }};">
                                {{ $inv->blood_group }}
                            </div>
                            <div class="fw-700" style="font-size:18px;color:#1e293b;">{{ number_format($inv->quantity) }}</div>
                            <div style="font-size:11px;color:#94a3b8;">ml available</div>
                            @if($inv->quantity < 500)
                                <span class="badge bg-danger mt-1" style="font-size:10px;">Critical</span>
                            @elseif($inv->quantity < 1000)
                                <span class="badge bg-warning text-dark mt-1" style="font-size:10px;">Low</span>
                            @else
                                <span class="badge bg-success mt-1" style="font-size:10px;">Good</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-3">
                        <i class="fas fa-box-open me-2"></i> No inventory records yet.
                        <a href="{{ route('blood-inventory.create') }}" class="ms-2 text-danger">Add stock</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity Tables --}}
<div class="row g-4">
    {{-- Recent Collections --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-syringe me-2 text-danger"></i>Recent Collections</div>
                <a href="{{ route('blood-collections.index') }}" class="btn btn-sm btn-blood-outline">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Donor</th>
                            <th>Group</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCollections ?? [] as $col)
                        <tr>
                            <td>{{ $col->donor->full_name ?? 'N/A' }}</td>
                            <td><span class="blood-group-pill">{{ $col->blood_group }}</span></td>
                            <td>{{ $col->donation_date ? $col->donation_date->format('d M Y') : '—' }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($col->screening_result) }}">
                                    {{ $col->screening_result }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No collections yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Requests --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-clipboard-list me-2 text-danger"></i>Recent Requests</div>
                <a href="{{ route('blood-requests.index') }}" class="btn btn-sm btn-blood-outline">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Hospital</th>
                            <th>Group</th>
                            <th>Urgency</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests ?? [] as $req)
                        <tr>
                            <td>{{ Str::limit($req->hospital->hospital_name ?? 'N/A', 18) }}</td>
                            <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($req->urgency_level) }}">
                                    {{ $req->urgency_level }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status badge-{{ strtolower($req->status) }}">
                                    {{ $req->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No requests yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ DONOR DASHBOARD --}}
@elseif(Auth::user()->role === 'donor')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;"><i class="fas fa-hand-holding-heart" style="color:#dc2626;"></i></div>
            <div>
                <div class="stat-label">Total Donations</div>
                <div class="stat-value">{{ $totalDonations ?? 0 }}</div>
                <div class="stat-meta">Blood contributions</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;"><i class="fas fa-calendar-check" style="color:#2563eb;"></i></div>
            <div>
                <div class="stat-label">Last Donation</div>
                <div class="stat-value" style="font-size:18px;">
                    {{ isset($lastDonationDate) ? \Carbon\Carbon::parse($lastDonationDate)->format('d M Y') : 'N/A' }}
                </div>
                <div class="stat-meta">Most recent date</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ isset($canDonate) && $canDonate ? '#dcfce7' : '#fef3c7' }};"><i class="fas fa-{{ isset($canDonate) && $canDonate ? 'check-circle' : 'clock' }}" style="color:{{ isset($canDonate) && $canDonate ? '#16a34a' : '#d97706' }};"></i></div>
            <div>
                <div class="stat-label">Next Eligible</div>
                <div class="stat-value" style="font-size:16px;">
                    @if(isset($nextEligibleDate))
                        {{ $nextEligibleDate->format('d M Y') }}
                    @else
                        Ready Now
                    @endif
                </div>
                <div class="stat-meta">
                    @if(isset($canDonate) && $canDonate)
                        <span class="up"><i class="fas fa-check me-1"></i>Eligible to donate</span>
                    @elseif(isset($nextEligibleDate))
                        <span class="down">{{ \Carbon\Carbon::now()->diffInDays($nextEligibleDate) }} days remaining</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($donorProfile ?? null)
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-user me-2 text-danger"></i>My Profile</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th style="width:40%;font-size:12.5px;color:#94a3b8;">Blood Group</th><td><span class="blood-group-pill fs-5">{{ $donorProfile->blood_group }}</span></td></tr>
                    <tr><th style="font-size:12.5px;color:#94a3b8;">Status</th><td><span class="badge-status badge-{{ strtolower($donorProfile->status) }}">{{ $donorProfile->status }}</span></td></tr>
                    <tr><th style="font-size:12.5px;color:#94a3b8;">Gender</th><td>{{ $donorProfile->gender }}</td></tr>
                    <tr><th style="font-size:12.5px;color:#94a3b8;">Phone</th><td>{{ $donorProfile->phone }}</td></tr>
                    <tr><th style="font-size:12.5px;color:#94a3b8;">Address</th><td>{{ $donorProfile->address }}</td></tr>
                </table>
                <div class="mt-3">
                    <a href="{{ route('donors.edit', $donorProfile->id) }}" class="btn btn-blood btn-sm me-2">
                        <i class="fas fa-edit me-1"></i>Edit Profile
                    </a>
                    <a href="{{ route('blood-collections.index') }}" class="btn btn-blood-outline btn-sm">
                        <i class="fas fa-history me-1"></i>Donation History
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-syringe me-2 text-danger"></i>Recent Donations</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Qty (ml)</th><th>Result</th></tr></thead>
                    <tbody>
                        @forelse($donationHistory ?? [] as $col)
                        <tr>
                            <td>{{ $col->donation_date ? $col->donation_date->format('d M Y') : '—' }}</td>
                            <td>{{ number_format($col->quantity) }}</td>
                            <td><span class="badge-status badge-{{ strtolower($col->screening_result) }}">{{ $col->screening_result }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No donations recorded yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="fas fa-calendar-check me-2 text-danger"></i>Upcoming Appointments</div>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-blood-outline">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Time</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($upcomingAppointments ?? [] as $apt)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</td>
                            <td><span class="badge-status badge-{{ strtolower($apt->status) }}">{{ $apt->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No upcoming appointments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============================================================ HOSPITAL DASHBOARD --}}
@elseif(Auth::user()->role === 'hospital')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;"><i class="fas fa-clipboard-list" style="color:#2563eb;"></i></div>
            <div>
                <div class="stat-label">Total Requests</div>
                <div class="stat-value">{{ $totalRequests ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;"><i class="fas fa-clock" style="color:#d97706;"></i></div>
            <div>
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $pendingRequests ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;"><i class="fas fa-check-circle" style="color:#16a34a;"></i></div>
            <div>
                <div class="stat-label">Approved</div>
                <div class="stat-value">{{ $approvedRequests ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e8ff;"><i class="fas fa-truck-medical" style="color:#7c3aed;"></i></div>
            <div>
                <div class="stat-label">Fulfilled</div>
                <div class="stat-value">{{ $fulfilledRequests ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-clipboard-list me-2 text-danger"></i>My Blood Requests</div>
                <a href="{{ route('blood-requests.create') }}" class="btn btn-sm btn-blood">
                    <i class="fas fa-plus me-1"></i>New Request
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Patient</th><th>Group</th><th>Qty</th><th>Urgency</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentRequests ?? [] as $req)
                        <tr>
                            <td>{{ $req->patient_name }}</td>
                            <td><span class="blood-group-pill">{{ $req->blood_group }}</span></td>
                            <td>{{ number_format($req->quantity) }} ml</td>
                            <td><span class="badge-status badge-{{ strtolower($req->urgency_level) }}">{{ $req->urgency_level }}</span></td>
                            <td><span class="badge-status badge-{{ strtolower($req->status) }}">{{ $req->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No requests submitted yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="fas fa-droplet me-2 text-danger"></i>Blood Availability</div>
                <a href="{{ route('blood-inventory.view') }}" class="btn btn-sm btn-blood-outline">Full Inventory</a>
            </div>
            <div class="card-body">
                @forelse($bloodGroupsData ?? [] as $inv)
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div>
                            <span class="blood-group-pill">{{ $inv->blood_group }}</span>
                            @if($inv->quantity <= 0)
                                <span class="badge bg-danger ms-2" style="font-size:10px;">Out of Stock</span>
                            @elseif($inv->quantity < 500)
                                <span class="badge bg-danger ms-2" style="font-size:10px;">Critical Low</span>
                            @elseif($inv->quantity < 1000)
                                <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">Low Stock</span>
                            @else
                                <span class="badge bg-success ms-2" style="font-size:10px;">Available</span>
                            @endif
                        </div>
                        <span style="font-size:12px;font-weight:600;color:#1e293b;">{{ number_format($inv->quantity) }} ml</span>
                    </div>
                    <div class="progress" style="height:6px;border-radius:4px;">
                        <div class="progress-bar {{ $inv->quantity < 500 ? 'bg-danger' : ($inv->quantity < 1000 ? 'bg-warning' : 'bg-success') }}"
                            style="width:{{ min(($inv->quantity / 3000) * 100, 100) }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">No inventory data</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if(in_array(Auth::user()->role, ['admin', 'staff']))
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Monthly Collections Bar Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        const labels = @isset($chartLabels) {!! $chartLabels !!} @else [] @endisset;
        const values = @isset($chartValues) {!! $chartValues !!} @else [] @endisset;

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Collections',
                    data: values,
                    backgroundColor: 'rgba(220,38,38,0.15)',
                    borderColor: '#dc2626',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' collections' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Blood Group Donut Chart
    const donutCtx = document.getElementById('donutChart');
    if (donutCtx) {
        const donutLabels = @isset($donutLabels) {!! $donutLabels !!} @else [] @endisset;
        const donutValues = @isset($donutValues) {!! $donutValues !!} @else [] @endisset;

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutValues,
                    backgroundColor: [
                        '#dc2626','#ef4444','#f87171','#fca5a5',
                        '#16a34a','#22c55e','#4ade80','#86efac'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed.toLocaleString() + ' ml' } }
                }
            }
        });
    }
});
</script>
@endif
@endpush
