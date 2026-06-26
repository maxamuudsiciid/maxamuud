@extends('layouts.app')
@section('title','Blood Collections')
@section('page-title','Blood Collections')
@section('breadcrumb')
    <li class="breadcrumb-item active">Blood Collections</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Collections</h2>
        <p class="page-sub">Track all blood donation collections</p>
    </div>
    @if(in_array(Auth::user()->role, ['admin', 'staff']))
    <a href="{{ route('blood-collections.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>Record Collection
    </a>
    @endif
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-syringe me-2 text-danger"></i>All Blood Collections</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>Qty (ml)</th>
                        <th>Donation Date</th>
                        <th>Expiry Date</th>
                        <th>Screening</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $i => $col)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ $col->donor ? route('donors.show', $col->donor->id) : '#' }}" style="font-weight:600;color:#1e293b;text-decoration:none;">
                                {{ $col->donor->full_name ?? 'N/A' }}
                            </a>
                        </td>
                        <td><span class="blood-group-pill">{{ $col->blood_group }}</span></td>
                        <td>{{ number_format($col->quantity) }}</td>
                        <td>{{ $col->donation_date ? $col->donation_date->format('d M Y') : '—' }}</td>
                        <td>
                            @if($col->expiry_date)
                                @php $expired = $col->expiry_date->isPast(); $soonExpiry = !$expired && $col->expiry_date->diffInDays(now()) <= 7; @endphp
                                <span class="{{ $expired ? 'text-danger fw-600' : ($soonExpiry ? 'text-warning fw-600' : 'text-success') }}">
                                    {{ $col->expiry_date->format('d M Y') }}
                                    @if($expired) <i class="fas fa-exclamation-circle ms-1"></i>
                                    @elseif($soonExpiry) <i class="fas fa-clock ms-1"></i>
                                    @endif
                                </span>
                            @else —
                            @endif
                        </td>
                        <td>
                            @if(in_array(Auth::user()->role,['admin','staff']) && $col->screening_result === 'Pending')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('blood-collections.updateScreening', $col->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="screening_result" value="Safe">
                                        <button type="submit" class="btn btn-sm" style="background:#dcfce7;color:#166534;border:none;font-size:11px;">
                                            <i class="fas fa-check me-1"></i>Safe
                                        </button>
                                    </form>
                                    <form action="{{ route('blood-collections.updateScreening', $col->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="screening_result" value="Unsafe">
                                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;font-size:11px;">
                                            <i class="fas fa-times me-1"></i>Unsafe
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge-status badge-{{ strtolower($col->screening_result) }}">{{ $col->screening_result }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('blood-collections.show', $col->id) }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array(Auth::user()->role,['admin','staff']))
                                <a href="{{ route('blood-collections.edit', $col->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('blood-collections.destroy', $col->id) }}" method="POST" class="confirm-delete">
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
                                <i class="fas fa-syringe"></i>
                                <p>No blood collections recorded yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($collections,'hasPages') && $collections->hasPages())
    <div class="card-body border-top py-3">{{ $collections->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
