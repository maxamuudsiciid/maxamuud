@extends('layouts.app')
@section('title','Edit Inventory')
@section('page-title','Edit Inventory')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-inventory.index') }}">Blood Inventory</a></li>
    <li class="breadcrumb-item active">Edit {{ $inventory->blood_group }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Blood Inventory</h2>
        <p class="page-sub">Update stock for blood group: {{ $inventory->blood_group }}</p>
    </div>
    <a href="{{ route('blood-inventory.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Update Stock — {{ $inventory->blood_group }}</div>
            <div class="card-body">
                <form action="{{ route('blood-inventory.update', $inventory->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Blood Group</label>
                        <input class="form-control" value="{{ $inventory->blood_group }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Quantity (ml)</label>
                        <input class="form-control" value="{{ number_format($inventory->quantity) }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Set New Quantity (ml) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity', $inventory->quantity) }}" min="0" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">This sets the absolute quantity, not an addition.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update</button>
                        <a href="{{ route('blood-inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
