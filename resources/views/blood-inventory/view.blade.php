@extends('layouts.app')
@section('title','Blood Inventory')
@section('page-title','Blood Inventory')
@section('breadcrumb')
    <li class="breadcrumb-item active">View Inventory</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Inventory</h2>
        <p class="page-sub">View currently available blood stock across all groups</p>
    </div>
    <a href="{{ route('blood-requests.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>New Blood Request
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div><i class="fas fa-box-open me-2 text-danger"></i>Current Stock Levels</div>
                <span class="badge bg-light text-dark border"><i class="fas fa-info-circle me-1 text-muted"></i> Updated real-time</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($inventory ?? [] as $inv)
                        @php
                            if($inv->quantity <= 0) {
                                $bg = '#fef2f2'; $border = '#fecaca'; $text = '#dc2626'; $status = 'Out of Stock'; $badge = 'bg-danger';
                            } elseif($inv->quantity < 500) {
                                $bg = '#fef2f2'; $border = '#fecaca'; $text = '#dc2626'; $status = 'Critical Low'; $badge = 'bg-danger';
                            } elseif($inv->quantity < 1000) {
                                $bg = '#fffbeb'; $border = '#fde68a'; $text = '#d97706'; $status = 'Low Stock'; $badge = 'bg-warning text-dark';
                            } else {
                                $bg = '#f0fdf4'; $border = '#bbf7d0'; $text = '#16a34a'; $status = 'Available'; $badge = 'bg-success';
                            }
                        @endphp
                        <div class="col-xl-3 col-md-4 col-6">
                            <div class="p-3 rounded-3 text-center h-100" style="background:{{ $bg }}; border:1.5px solid {{ $border }};">
                                <div class="fs-3 fw-800" style="color:{{ $text }};">{{ $inv->blood_group }}</div>
                                <div class="fw-700 mt-2" style="font-size:22px;color:#1e293b;">{{ number_format($inv->quantity) }}</div>
                                <div style="font-size:12px;color:#64748b;margin-bottom:8px;">milliliters (ml)</div>
                                <span class="badge {{ $badge }} mt-1" style="font-size:11px; padding: 5px 10px;">{{ $status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-5">
                            <i class="fas fa-box-open mb-3" style="font-size: 32px; opacity: 0.5;"></i>
                            <p>No inventory records found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
