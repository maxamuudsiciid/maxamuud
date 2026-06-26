@extends('layouts.app')
@section('title','Edit Collection')
@section('page-title','Edit Blood Collection')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-collections.index') }}">Blood Collections</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Blood Collection</h2>
        <p class="page-sub">Update collection record #{{ $collection->id }}</p>
    </div>
    <a href="{{ route('blood-collections.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Edit Collection #{{ $collection->id }}</div>
            <div class="card-body">
                <form action="{{ route('blood-collections.update', $collection->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Donor</label>
                            <input class="form-control" value="{{ $collection->donor->full_name ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <input class="form-control" value="{{ $collection->blood_group }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity (ml) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $collection->quantity) }}" min="1" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Donation Date <span class="text-danger">*</span></label>
                            <input type="date" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror"
                                value="{{ old('donation_date', $collection->donation_date ? $collection->donation_date->format('Y-m-d') : '') }}" required>
                            @error('donation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Screening Result <span class="text-danger">*</span></label>
                            <select name="screening_result" class="form-select @error('screening_result') is-invalid @enderror" required>
                                <option value="Pending" {{ old('screening_result',$collection->screening_result)=='Pending'?'selected':'' }}>Pending</option>
                                <option value="Safe" {{ old('screening_result',$collection->screening_result)=='Safe'?'selected':'' }}>Safe</option>
                                <option value="Unsafe" {{ old('screening_result',$collection->screening_result)=='Unsafe'?'selected':'' }}>Unsafe</option>
                            </select>
                            @error('screening_result')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update</button>
                        <a href="{{ route('blood-collections.show', $collection->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
