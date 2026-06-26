@extends('layouts.app')
@section('title','Blood Inventory')
@section('page-title','Blood Inventory')
@section('breadcrumb')
    <li class="breadcrumb-item active">Blood Inventory</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Inventory</h2>
        <p class="page-sub">Current blood stock levels by group</p>
    </div>
    <a href="{{ route('blood-inventory.create') }}" class="btn btn-blood">
        <i class="fas fa-plus me-2"></i>Add Stock
    </a>
</div>

{{-- Inventory Cards --}}
<div class="row g-3 mb-4">
    @forelse($inventory as $inv)
    <div class="col-xl-3 col-md-4 col-6">
        <div class="card text-center py-3">
            <div class="card-body">
                <div class="fs-1 fw-800 mb-1" style="color:{{ $inv->quantity < 500 ? '#dc2626' : ($inv->quantity < 1000 ? '#d97706' : '#16a34a') }};">
                    {{ $inv->blood_group }}
                </div>
                <div class="fw-700" style="font-size:22px;color:#1e293b;">{{ number_format($inv->quantity) }}</div>
                <div class="text-muted mb-2" style="font-size:12px;">ml available</div>
                <div class="progress mb-2" style="height:6px;border-radius:4px;">
                    <div class="progress-bar {{ $inv->quantity < 500 ? 'bg-danger' : ($inv->quantity < 1000 ? 'bg-warning' : 'bg-success') }}"
                        style="width:{{ min(($inv->quantity / 3000)*100, 100) }}%"></div>
                </div>
                @if($inv->quantity < 500)
                    <span class="badge bg-danger" style="font-size:10px;">Critical Low</span>
                @elseif($inv->quantity < 1000)
                    <span class="badge bg-warning text-dark" style="font-size:10px;">Low Stock</span>
                @else
                    <span class="badge bg-success" style="font-size:10px;">Sufficient</span>
                @endif
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="{{ route('blood-inventory.edit', $inv->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('blood-inventory.destroy', $inv->id) }}" method="POST" class="confirm-delete">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state card py-5">
            <i class="fas fa-box-open"></i>
            <p>No inventory records. <a href="{{ route('blood-inventory.create') }}" class="text-danger">Add blood stock</a>.</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Inventory Table --}}
<div class="card">
    <div class="card-header"><i class="fas fa-table me-2 text-danger"></i>Inventory Summary Table</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Quantity (ml)</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory as $inv)
                <tr>
                    <td><span class="blood-group-pill fs-6">{{ $inv->blood_group }}</span></td>
                    <td><strong>{{ number_format($inv->quantity) }} ml</strong></td>
                    <td>
                        @if($inv->quantity < 500)
                            <span class="badge-status badge-unsafe">Critical</span>
                        @elseif($inv->quantity < 1000)
                            <span class="badge-status badge-pending">Low</span>
                        @else
                            <span class="badge-status badge-safe">Sufficient</span>
                        @endif
                    </td>
                    <td>{{ $inv->updated_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('blood-inventory.edit', $inv->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('blood-inventory.destroy', $inv->id) }}" method="POST" class="confirm-delete">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No inventory records</td></tr>
                @endforelse
            </tbody>
            @if($inventory->isNotEmpty())
            <tfoot>
                <tr style="background:#f8fafc;">
                    <th>Total</th>
                    <th>{{ number_format($inventory->sum('quantity')) }} ml</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
