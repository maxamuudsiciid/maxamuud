@extends('layouts.app')
@section('title','Record Blood Test')
@section('page-title','Blood Testing')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-tests.index') }}">Blood Tests</a></li>
    <li class="breadcrumb-item active">Record Test</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Record Blood Test</h2>
        <p class="page-sub">Enter lab screening results for a blood collection</p>
    </div>
    <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-microscope me-2 text-danger"></i>Test Details</div>
            <div class="card-body">
                <form action="{{ route('blood-tests.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Blood Collection (Donor) <span class="text-danger">*</span></label>
                            <select name="blood_collection_id" class="form-select @error('blood_collection_id') is-invalid @enderror" required>
                                <option value="">Select collection...</option>
                                @foreach($collections as $col)
                                    <option value="{{ $col->id }}" {{ old('blood_collection_id')==$col->id?'selected':'' }}>
                                        #{{ $col->id }} — {{ $col->donor->full_name ?? 'Unknown' }}
                                        ({{ $col->blood_group }}, {{ $col->donation_date ? $col->donation_date->format('d M Y') : '—' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('blood_collection_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($collections->isEmpty())
                                <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>All collections already have tests recorded.</small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Test Date <span class="text-danger">*</span></label>
                            <input type="date" name="test_date" class="form-control @error('test_date') is-invalid @enderror"
                                value="{{ old('test_date', date('Y-m-d')) }}" required>
                            @error('test_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4 mb-2">
                        <h6 class="fw-700" style="font-size:13px;color:#1e293b;">
                            <i class="fas fa-vial me-2 text-danger"></i>Test Results
                        </h6>
                        <p class="text-muted" style="font-size:12.5px;">Any Positive result will mark the collection as Unsafe and remove it from eligible inventory.</p>
                    </div>

                    <div class="row g-3">
                        @foreach(['hiv_result'=>'HIV','hbv_result'=>'HBV (Hepatitis B)','hcv_result'=>'HCV (Hepatitis C)','syphilis_result'=>'Syphilis'] as $field => $label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <div class="form-check flex-fill p-3 rounded-2" style="border:1.5px solid {{ old($field)==='Negative'?'#16a34a':'#e2e8f0' }};background:{{ old($field)==='Negative'?'#f0fdf4':'#f8fafc' }};">
                                    <input class="form-check-input result-radio" type="radio" name="{{ $field }}" id="{{ $field }}_neg" value="Negative"
                                        {{ old($field,'Negative')==='Negative'?'checked':'' }} required>
                                    <label class="form-check-label fw-600" for="{{ $field }}_neg" style="color:#16a34a;">
                                        <i class="fas fa-check me-1"></i>Negative
                                    </label>
                                </div>
                                <div class="form-check flex-fill p-3 rounded-2" style="border:1.5px solid {{ old($field)==='Positive'?'#dc2626':'#e2e8f0' }};background:{{ old($field)==='Positive'?'#fef2f2':'#f8fafc' }};">
                                    <input class="form-check-input result-radio" type="radio" name="{{ $field }}" id="{{ $field }}_pos" value="Positive"
                                        {{ old($field)==='Positive'?'checked':'' }}>
                                    <label class="form-check-label fw-600" for="{{ $field }}_pos" style="color:#dc2626;">
                                        <i class="fas fa-times me-1"></i>Positive
                                    </label>
                                </div>
                            </div>
                            @error($field)<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                        </div>
                        @endforeach

                        <div class="col-12">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional lab notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Save Test Results</button>
                        <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
