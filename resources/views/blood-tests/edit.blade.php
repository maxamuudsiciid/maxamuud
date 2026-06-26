@extends('layouts.app')
@section('title','Edit Blood Test')
@section('page-title','Blood Testing')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('blood-tests.index') }}">Blood Tests</a></li>
    <li class="breadcrumb-item active">Edit Test #{{ $blood_test->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Blood Test #{{ $blood_test->id }}</h2>
        <p class="page-sub">Update lab screening results</p>
    </div>
    <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-danger"></i>Update Test Results</div>
            <div class="card-body">
                @if($blood_test->bloodCollection)
                <div class="p-3 mb-4 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <div class="fw-600" style="font-size:13px;">Collection: #{{ $blood_test->blood_collection_id }}</div>
                    <div class="text-muted" style="font-size:12.5px;">
                        Donor: {{ $blood_test->bloodCollection->donor->full_name ?? 'N/A' }} |
                        Blood Group: {{ $blood_test->bloodCollection->blood_group }} |
                        Date: {{ $blood_test->bloodCollection->donation_date ? $blood_test->bloodCollection->donation_date->format('d M Y') : '—' }}
                    </div>
                </div>
                @endif

                <form action="{{ route('blood-tests.update', $blood_test->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Test Date <span class="text-danger">*</span></label>
                        <input type="date" name="test_date" class="form-control @error('test_date') is-invalid @enderror"
                            value="{{ old('test_date', $blood_test->test_date ? $blood_test->test_date->format('Y-m-d') : '') }}" required>
                        @error('test_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        @foreach(['hiv_result'=>'HIV','hbv_result'=>'HBV (Hepatitis B)','hcv_result'=>'HCV (Hepatitis C)','syphilis_result'=>'Syphilis'] as $field => $label)
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                            <select name="{{ $field }}" class="form-select @error($field) is-invalid @enderror" required>
                                <option value="Negative" {{ old($field,$blood_test->$field)==='Negative'?'selected':'' }}>Negative</option>
                                <option value="Positive" {{ old($field,$blood_test->$field)==='Positive'?'selected':'' }}>Positive</option>
                            </select>
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endforeach
                        <div class="col-12">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes',$blood_test->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-blood"><i class="fas fa-save me-2"></i>Update Test</button>
                        <a href="{{ route('blood-tests.show', $blood_test->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
