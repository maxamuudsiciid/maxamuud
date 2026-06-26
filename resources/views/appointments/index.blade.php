@extends('layouts.app')
@section('title','Appointments')
@section('page-title','Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">Appointments</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Donation Appointments</h2>
        <p class="page-sub">Manage blood donation schedules</p>
    </div>
    @if(Auth::user()->role !== 'hospital')
    <a href="{{ route('appointments.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>Book Appointment
    </a>
    @endif
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-calendar-check me-2 text-danger"></i>Appointments List</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        @if(Auth::user()->role !== 'donor')
                        <th>Donor</th>
                        @endif
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $i => $apt)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        @if(Auth::user()->role !== 'donor')
                        <td>
                            @if($apt->donor)
                            <a href="{{ route('donors.show', $apt->donor->id) }}" style="font-weight:600;color:#1e293b;text-decoration:none;">
                                {{ $apt->donor->full_name }}
                            </a>
                            @else
                            <span class="text-muted">Unknown</span>
                            @endif
                        </td>
                        @endif
                        <td>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</td>
                        <td>
                            @if(in_array(Auth::user()->role,['admin','staff']) && $apt->status === 'Pending')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button class="btn btn-sm" style="background:#dcfce7;color:#166534;border:none;font-size:11px;">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;font-size:11px;">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            @elseif(in_array(Auth::user()->role,['admin','staff']) && $apt->status === 'Approved')
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-status badge-approved">Approved</span>
                                    <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="status" value="Completed">
                                        <button class="btn btn-sm" style="background:#dbeafe;color:#1e40af;border:none;font-size:11px;">
                                            Mark Completed
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php
                                    $bClass = 'pending';
                                    if($apt->status == 'Approved') $bClass = 'safe';
                                    if($apt->status == 'Rejected') $bClass = 'unsafe';
                                    if($apt->status == 'Completed') $bClass = 'safe'; // or another color
                                @endphp
                                <span class="badge-status badge-{{ $bClass }}">{{ $apt->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if(Auth::user()->role !== 'hospital')
                                    @if(!in_array($apt->status, ['Completed', 'Rejected']) && !(\Carbon\Carbon::parse($apt->appointment_date)->isPast() && !\Carbon\Carbon::parse($apt->appointment_date)->isToday()))
                                        <a href="{{ route('appointments.edit', $apt->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Reschedule">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('appointments.destroy', $apt->id) }}" method="POST" class="confirm-delete">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Cancel">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-calendar"></i>
                                <p>No appointments found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($appointments,'hasPages') && $appointments->hasPages())
    <div class="card-body border-top py-3">{{ $appointments->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
