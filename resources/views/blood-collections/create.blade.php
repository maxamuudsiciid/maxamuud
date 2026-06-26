@extends('layouts.app')
@section('title','Record Blood Collection')
@section('page-title','Record Blood Collection')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-collections.index') }}">Blood Collections</a></li>
    <li class="breadcrumb-item active">Record New</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Record Blood Collection</h2>
        <p class="page-sub">Register a new blood donation from a donor</p>
    </div>
    <a href="{{ route('blood-collections.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-syringe me-2 text-danger"></i>Collection Details</div>
            <div class="card-body">
                <form action="{{ route('blood-collections.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Donor <span class="text-danger">*</span></label>
                            <select name="donor_id" class="form-select @error('donor_id') is-invalid @enderror" required>
                                <option value=""></option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}"
                                        data-blood="{{ $donor->blood_group }}"
                                        {{ old('donor_id', request('donor_id')) == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->full_name }} — {{ $donor->blood_group }} ({{ $donor->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('donor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity (ml) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', ) }}" min="1" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Standard unit: 450ml</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Donation Date <span class="text-danger">*</span></label>
                            <input type="date" name="donation_date" class="form-control @error('donation_date') is-invalid @enderror"
                                value="{{ old('donation_date', date('Y-m-d')) }}" required>
                            @error('donation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Initial Screening <span class="text-danger">*</span></label>
                            <select name="screening_result" class="form-select @error('screening_result') is-invalid @enderror" required>
                                <option value="Pending" {{ old('screening_result')=='Pending'?'selected':'' }}>Pending (awaiting test)</option>
                                <option value="Safe" {{ old('screening_result')=='Safe'?'selected':'' }}>Safe</option>
                                <option value="Unsafe" {{ old('screening_result')=='Unsafe'?'selected':'' }}>Unsafe</option>
                            </select>
                            @error('screening_result')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Blood Group</label>
                            <input type="text" id="blood_group_display" class="form-control" value="Auto-detected from donor" disabled>
                            <small class="text-muted">Automatically set from donor record</small>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3" style="font-size:13px;">
                        <i class="fas fa-info-circle me-2"></i>
                        Expiry date is automatically set to <strong>42 days</strong> after donation date. Blood marked as <strong>Safe</strong> will be added to inventory immediately.
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Record Collection</button>
                        <a href="{{ route('blood-collections.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('[name="donor_id"]').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const bg = opt.getAttribute('data-blood');
    document.getElementById('blood_group_display').value = bg ? bg : 'Auto-detected from donor';
});
// Trigger on load if pre-selected
document.querySelector('[name="donor_id"]').dispatchEvent(new Event('change'));
</script>
@endpush
