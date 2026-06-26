@extends('layouts.app')
@section('title','Add Blood Stock')
@section('page-title','Add Blood Stock')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-inventory.index') }}">Blood Inventory</a></li>
    <li class="breadcrumb-item active">Add Stock</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Add Blood Stock</h2>
        <p class="page-sub">Manually add stock to blood inventory</p>
    </div>
    <a href="{{ route('blood-inventory.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle me-2 text-danger"></i>Stock Entry</div>
            <div class="card-body">
                <form action="{{ route('blood-inventory.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                        <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" required>
                            <option value=""></option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity to Add (ml) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                            value="{{ old('quantity') }}" min="1" placeholder=>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">This quantity will be added to existing stock for the selected blood group.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Add Stock</button>
                        <a href="{{ route('blood-inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
