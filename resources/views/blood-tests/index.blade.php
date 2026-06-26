@extends('layouts.app')
@section('title','Blood Tests')
@section('page-title','Blood Testing')
@section('breadcrumb')
    <li class="breadcrumb-item active">Blood Tests</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Blood Tests Reports</h2>
        <p class="page-sub">Filter and view detailed screening test results</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('blood-tests.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-chart-pie me-2"></i>Dashboard
        </a>
        <a href="{{ route('blood-tests.create') }}" class="btn btn-blood">
            <i class="fas fa-plus me-2"></i>Record Test
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-filter me-2 text-danger"></i>Filter Reports</div>
    <div class="card-body">
        <form method="GET" action="{{ route('blood-tests.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Overall Result</label>
                <select name="overall_result" class="form-select">
                    <option value="">Any</option>
                    <option value="Safe" {{ request('overall_result')==='Safe'?'selected':'' }}>Safe</option>
                    <option value="Unsafe" {{ request('overall_result')==='Unsafe'?'selected':'' }}>Unsafe</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Test Type</label>
                <select name="test_type" class="form-select">
                    <option value="">Any</option>
                    <option value="hiv_result" {{ request('test_type')==='hiv_result'?'selected':'' }}>HIV</option>
                    <option value="hbv_result" {{ request('test_type')==='hbv_result'?'selected':'' }}>HBV</option>
                    <option value="hcv_result" {{ request('test_type')==='hcv_result'?'selected':'' }}>HCV</option>
                    <option value="syphilis_result" {{ request('test_type')==='syphilis_result'?'selected':'' }}>Syphilis</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Specific Result</label>
                <select name="result" class="form-select">
                    <option value="">Any</option>
                    <option value="Positive" {{ request('result')==='Positive'?'selected':'' }}>Positive</option>
                    <option value="Negative" {{ request('result')==='Negative'?'selected':'' }}>Negative</option>
                </select>
            </div>
            <div class="col-12 mt-3 text-end">
                <button type="submit" class="btn btn-blood px-4"><i class="fas fa-search me-2"></i>Filter Tests</button>
                <a href="{{ route('blood-tests.index') }}" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-microscope me-2 text-danger"></i>All Blood Tests</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>Test Date</th>
                        <th>HIV</th>
                        <th>HBV</th>
                        <th>HCV</th>
                        <th>Syphilis</th>
                        <th>Overall</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $i => $test)
                    @php
                        $allClear = $test->hiv_result==='Negative' && $test->hbv_result==='Negative'
                                 && $test->hcv_result==='Negative' && $test->syphilis_result==='Negative';
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($test->bloodCollection && $test->bloodCollection->donor)
                                <a href="{{ route('donors.show',$test->bloodCollection->donor->id) }}" style="font-weight:600;color:#1e293b;text-decoration:none;">
                                    {{ $test->bloodCollection->donor->full_name }}
                                </a>
                            @else N/A @endif
                        </td>
                        <td>
                            @if($test->bloodCollection)
                                <span class="blood-group-pill">{{ $test->bloodCollection->blood_group }}</span>
                            @else — @endif
                        </td>
                        <td>{{ $test->test_date ? $test->test_date->format('d M Y') : '—' }}</td>
                        @foreach(['hiv_result','hbv_result','hcv_result','syphilis_result'] as $field)
                        <td>
                            <span class="badge-status {{ $test->$field==='Negative' ? 'badge-safe' : 'badge-unsafe' }}">
                                {{ $test->$field }}
                            </span>
                        </td>
                        @endforeach
                        <td>
                            <span class="badge-status {{ $allClear ? 'badge-safe' : 'badge-unsafe' }}">
                                {{ $allClear ? 'Safe' : 'Unsafe' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('blood-tests.show', $test->id) }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b;" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('blood-tests.edit', $test->id) }}" class="btn btn-sm" style="background:#dbeafe;color:#2563eb;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('blood-tests.destroy', $test->id) }}" method="POST" class="confirm-delete">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-microscope"></i>
                                <p>No blood tests recorded yet. <a href="{{ route('blood-tests.create') }}" class="text-danger">Record first test</a>.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($tests,'hasPages') && $tests->hasPages())
    <div class="card-body border-top py-3">{{ $tests->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
