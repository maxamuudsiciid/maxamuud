@extends('layouts.app')

@section('title', 'Donors')
@section('page-title', 'Donors')
@section('breadcrumb')
    <li class="breadcrumb-item active">Donors</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Donor Management</h2>
        <p class="page-sub">Manage all registered blood donors</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin','staff']))
    <a href="{{ route('donors.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>Add Donor
    </a>
    @endif
</div>

{{-- Filter Bar --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('donors.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select form-select-sm">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Deferred" {{ request('status') == 'Deferred' ? 'selected' : '' }}>Deferred</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-blood btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('donors.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="fas fa-times me-1"></i>Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div><i class="fas fa-users me-2 text-danger"></i>All Donors ({{ $donors->total() }})</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Blood Group</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Last Donation</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $i => $donor)
                    <tr>
                        <td>{{ $donors->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $canViewImage = in_array(Auth::user()->role, ['admin', 'staff']) || (Auth::user()->role === 'donor' && Auth::user()->donor && Auth::user()->donor->id === $donor->id);
                                @endphp
                                @if($donor->user && $donor->user->image && $canViewImage)
                                    <img src="{{ asset('storage/profiles/'.$donor->user->image) }}" alt="Profile" style="width:32px;height:32px;border-radius:50%;object-fit:cover;cursor:pointer;flex-shrink:0;" onclick="window.open(this.src, '_blank')">
                                @else
                                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#7f1d1d);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                                        {{ strtoupper(substr($donor->full_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;font-size:13.5px;">{{ $donor->full_name }}</div>
                                    <div style="font-size:11.5px;color:#94a3b8;">{{ $donor->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="blood-group-pill">{{ $donor->blood_group }}</span></td>
                        <td>{{ $donor->gender }}</td>
                        <td>{{ $donor->phone }}</td>
                        <td>
                            @if($donor->last_donation_date)
                                {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge-status badge-{{ strtolower($donor->status) }}">{{ $donor->status }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('donors.show', $donor->id) }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array(Auth::user()->role, ['admin','staff']) || (Auth::user()->role === 'donor' && Auth::user()->donor && Auth::user()->donor->id === $donor->id))
                                <a href="{{ route('donors.edit', $donor->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(in_array(Auth::user()->role, ['admin','staff']))
                                <form action="{{ route('donors.destroy', $donor->id) }}" method="POST" class="confirm-delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>No donors found. <a href="{{ route('donors.create') }}" class="text-danger">Add the first donor</a>.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($donors->hasPages())
    <div class="card-body border-top py-3">
        {{ $donors->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
