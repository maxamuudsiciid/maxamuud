@extends('layouts.app')
@section('title','Distributions')
@section('page-title','Blood Distribution')
@section('breadcrumb')
    <li class="breadcrumb-item active">Distributions</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Distribution</h2>
        <p class="page-sub">Manage blood distribution records to hospitals</p>
    </div>
    <a href="{{ route('distributions.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>New Distribution
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-truck-medical me-2 text-danger"></i>All Distributions ({{ $distributions->total() }})</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hospital</th>
                        <th>Blood Group</th>
                        <th>Qty (ml)</th>
                        <th>Distribution Date</th>
                        <th>Blood Request</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions as $i => $dist)
                    <tr>
                        <td>{{ $distributions->firstItem() + $i }}</td>
                        <td>{{ $dist->hospital->hospital_name ?? 'N/A' }}</td>
                        <td><span class="blood-group-pill">{{ $dist->blood_group }}</span></td>
                        <td>{{ number_format($dist->quantity) }}</td>
                        <td>{{ $dist->distribution_date ? $dist->distribution_date->format('d M Y') : '—' }}</td>
                        <td>
                            @if($dist->bloodRequest)
                                <span class="badge" style="background:#f0fdf4;color:#166534;font-size:11px;">
                                    Req #{{ $dist->blood_request_id }}
                                </span>
                            @else — @endif
                        </td>
                        <td>{{ $dist->approvedBy->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('distributions.show', $dist->id) }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-truck-medical"></i>
                                <p>No distribution records found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($distributions->hasPages())
    <div class="card-body border-top py-3">{{ $distributions->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
