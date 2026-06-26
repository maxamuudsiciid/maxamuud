@extends('layouts.app')
@section('title','Hospitals')
@section('page-title','Hospitals')
@section('breadcrumb')
    <li class="breadcrumb-item active">Hospitals</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Hospital Management</h2>
        <p class="page-sub">Manage registered hospital accounts</p>
    </div>
    @if(in_array(Auth::user()->role,['admin','staff']))
    <a href="{{ route('hospitals.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>Add Hospital
    </a>
    @endif
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-hospital me-2 text-danger"></i>All Hospitals ({{ $hospitals->total() }})</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hospital Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hospitals as $i => $hospital)
                    <tr>
                        <td>{{ $hospitals->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $canViewImage = in_array(Auth::user()->role, ['admin', 'staff']) || (Auth::user()->role === 'hospital' && Auth::user()->hospital && Auth::user()->hospital->id === $hospital->id);
                                @endphp
                                @if($hospital->user && $hospital->user->image && $canViewImage)
                                    <img src="{{ asset('storage/profiles/'.$hospital->user->image) }}" alt="Profile" style="width:32px;height:32px;border-radius:8px;object-fit:cover;cursor:pointer;flex-shrink:0;" onclick="window.open(this.src, '_blank')">
                                @else
                                    <i class="fas fa-hospital text-danger" style="font-size:16px;"></i>
                                @endif
                                <div style="font-weight:700;font-size:13.5px;">
                                    {{ $hospital->hospital_name }}
                                </div>
                            </div>
                        </td>
                        <td>{{ $hospital->contact_person }}</td>
                        <td>{{ $hospital->phone }}</td>
                        <td>{{ $hospital->email }}</td>
                        <td>{{ Str::limit($hospital->address, 35) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('hospitals.show', $hospital->id) }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('hospitals.edit', $hospital->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <form action="{{ route('hospitals.destroy', $hospital->id) }}" method="POST" class="confirm-delete">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-hospital"></i>
                                <p>No hospitals registered. <a href="{{ route('hospitals.create') }}" class="text-danger">Add one now</a>.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($hospitals->hasPages())
    <div class="card-body border-top py-3">{{ $hospitals->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
