@extends('layouts.app')
@section('title','Reports')
@section('page-title','Reports & Analytics')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Reports & Analytics</h2>
        <p class="page-sub">Generate and view system reports</p>
    </div>
</div>

{{-- Quick Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#fee2e2;"><i class="fas fa-users" style="color:#dc2626;"></i></div><div><div class="stat-label">Total Donors</div><div class="stat-value">{{ $totalDonors }}</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#dcfce7;"><i class="fas fa-syringe" style="color:#16a34a;"></i></div><div><div class="stat-label">Total Collections</div><div class="stat-value">{{ $totalCollections }}</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#dbeafe;"><i class="fas fa-clipboard-list" style="color:#2563eb;"></i></div><div><div class="stat-label">Total Requests</div><div class="stat-value">{{ $totalRequests }}</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon" style="background:#f3e8ff;"><i class="fas fa-truck-medical" style="color:#7c3aed;"></i></div><div><div class="stat-label">Total Distributions</div><div class="stat-value">{{ $totalDistributions }}</div></div></div></div>
</div>

{{-- Date Filter + Report Type --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2 text-danger"></i>Report Filters</div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Report Type</label>
                <select name="type" class="form-select">
                    <option value="collections" {{ request('type','collections')==='collections'?'selected':'' }}>Blood Collections</option>
                    <option value="requests" {{ request('type')==='requests'?'selected':'' }}>Blood Requests</option>
                    <option value="distributions" {{ request('type')==='distributions'?'selected':'' }}>Distributions</option>
                    <option value="inventory" {{ request('type')==='inventory'?'selected':'' }}>Inventory Summary</option>
                    <option value="donors" {{ request('type')==='donors'?'selected':'' }}>Donor Report</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-blood"><i class="fas fa-search me-2"></i>Generate</button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary ms-1"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Report Table --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <i class="fas fa-table me-2 text-danger"></i>
            {{ ucfirst(request('type','collections')) }} Report
            @if(request('from') || request('to'))
                <span class="text-muted ms-2" style="font-size:12px;">
                    {{ request('from') ? 'From '.request('from') : '' }}
                    {{ request('to') ? ' To '.request('to') : '' }}
                </span>
            @endif
        </div>
        <a href="{{ route('reports.print', array_filter(['type'=>request('type','collections'),'from'=>request('from'),'to'=>request('to')])) }}"
            class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" target="_blank">
            <i class="fas fa-print me-1"></i>Print
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            @php $type = request('type','collections'); @endphp

            @if($type === 'collections')
            <table class="table table-hover data-table mb-0">
                <thead><tr><th>#</th><th>Donor</th><th>Blood Group</th><th>Qty (ml)</th><th>Donation Date</th><th>Expiry</th><th>Screening</th></tr></thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td><td>{{ $row->donor->full_name ?? 'N/A' }}</td>
                        <td><span class="blood-group-pill">{{ $row->blood_group }}</span></td>
                        <td>{{ number_format($row->quantity) }}</td>
                        <td>{{ $row->donation_date?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $row->expiry_date?->format('d M Y') ?? '—' }}</td>
                        <td><span class="badge-status badge-{{ strtolower($row->screening_result) }}">{{ $row->screening_result }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No collections found for selected period.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @elseif($type === 'requests')
            <table class="table table-hover data-table mb-0">
                <thead><tr><th>#</th><th>Hospital</th><th>Patient</th><th>Blood Group</th><th>Qty (ml)</th><th>Urgency</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td><td>{{ $row->hospital->hospital_name ?? 'N/A' }}</td>
                        <td>{{ $row->patient_name }}</td>
                        <td><span class="blood-group-pill">{{ $row->blood_group }}</span></td>
                        <td>{{ number_format($row->quantity) }}</td>
                        <td><span class="badge-status badge-{{ strtolower($row->urgency_level) }}">{{ $row->urgency_level }}</span></td>
                        <td>{{ $row->request_date?->format('d M Y') ?? '—' }}</td>
                        <td><span class="badge-status badge-{{ strtolower($row->status) }}">{{ $row->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @elseif($type === 'distributions')
            <table class="table table-hover data-table mb-0">
                <thead><tr><th>#</th><th>Hospital</th><th>Blood Group</th><th>Qty (ml)</th><th>Date</th><th>Approved By</th></tr></thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td><td>{{ $row->hospital->hospital_name ?? 'N/A' }}</td>
                        <td><span class="blood-group-pill">{{ $row->blood_group }}</span></td>
                        <td>{{ number_format($row->quantity) }}</td>
                        <td>{{ $row->distribution_date?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $row->approvedBy->name ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No distributions found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @elseif($type === 'inventory')
            <table class="table table-hover mb-0">
                <thead><tr><th>Blood Group</th><th>Quantity (ml)</th><th>Status</th><th>Last Updated</th></tr></thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td><span class="blood-group-pill fs-6">{{ $row->blood_group }}</span></td>
                        <td><strong>{{ number_format($row->quantity) }}</strong></td>
                        <td>
                            @if($row->quantity < 500) <span class="badge-status badge-unsafe">Critical</span>
                            @elseif($row->quantity < 1000) <span class="badge-status badge-pending">Low</span>
                            @else <span class="badge-status badge-safe">Sufficient</span>
                            @endif
                        </td>
                        <td>{{ $row->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No inventory data.</td></tr>
                    @endforelse
                </tbody>
                @if($data->isNotEmpty())
                <tfoot><tr style="background:#f8fafc;"><th>Total</th><th>{{ number_format($data->sum('quantity')) }} ml</th><th colspan="2"></th></tr></tfoot>
                @endif
            </table>

            @elseif($type === 'donors')
            <table class="table table-hover data-table mb-0">
                <thead><tr><th>#</th><th>Name</th><th>Blood Group</th><th>Gender</th><th>Status</th><th>Last Donation</th><th>Donations Count</th></tr></thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td><td>{{ $row->full_name }}</td>
                        <td><span class="blood-group-pill">{{ $row->blood_group }}</span></td>
                        <td>{{ $row->gender }}</td>
                        <td><span class="badge-status badge-{{ strtolower($row->status) }}">{{ $row->status }}</span></td>
                        <td>{{ $row->last_donation_date?->format('d M Y') ?? '—' }}</td>
                        <td><strong>{{ $row->bloodCollections->count() }}</strong></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No donor data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
