<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ ucfirst(request('type','Report')) }} Report — BloodBank MS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #111; }
        .report-header { border-bottom: 2px solid #dc2626; margin-bottom: 20px; padding-bottom: 12px; }
        .report-header h1 { color: #dc2626; font-size: 22px; margin: 0; }
        .report-header .meta { font-size: 11px; color: #888; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #dc2626; color: #fff; padding: 8px 10px; text-align: left; font-size: 12px; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #fafafa; }
        .footer { margin-top: 30px; font-size: 11px; color: #888; border-top: 1px solid #eee; padding-top: 10px; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
<div class="no-print mb-3">
    <button onclick="window.print()" class="btn btn-sm btn-danger me-2">🖨️ Print</button>
    <button onclick="window.history.back()" class="btn btn-sm btn-outline-secondary">← Back</button>
</div>

<div class="report-header">
    <h1>🩸 BloodBank Management System</h1>
    <div class="meta">
        Report Type: <strong>{{ ucfirst(request('type','collections')) }}</strong>
        @if(request('from')) | From: <strong>{{ request('from') }}</strong> @endif
        @if(request('to')) | To: <strong>{{ request('to') }}</strong> @endif
        | Generated: <strong>{{ now()->format('d M Y H:i') }}</strong>
        | By: <strong>{{ Auth::user()->name }}</strong>
    </div>
</div>

@php $type = request('type','collections'); @endphp

@if($type === 'collections')
<table>
    <thead><tr><th>#</th><th>Donor</th><th>Blood Group</th><th>Qty (ml)</th><th>Donation Date</th><th>Screening</th></tr></thead>
    <tbody>
        @forelse($data as $i => $row)
        <tr><td>{{ $i+1 }}</td><td>{{ $row->donor->full_name ?? 'N/A' }}</td><td>{{ $row->blood_group }}</td>
            <td>{{ number_format($row->quantity) }}</td><td>{{ $row->donation_date?->format('d M Y') ?? '—' }}</td>
            <td>{{ $row->screening_result }}</td></tr>
        @empty<tr><td colspan="6" style="text-align:center;color:#888;">No data.</td></tr>
        @endforelse
        <tr style="background:#f8f8f8;font-weight:700;"><td colspan="3">Total</td><td>{{ number_format($data->sum('quantity')) }} ml</td><td colspan="2"></td></tr>
    </tbody>
</table>
@elseif($type === 'requests')
<table>
    <thead><tr><th>#</th><th>Hospital</th><th>Patient</th><th>Blood Group</th><th>Qty (ml)</th><th>Urgency</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
        @forelse($data as $i => $row)
        <tr><td>{{ $i+1 }}</td><td>{{ $row->hospital->hospital_name ?? 'N/A' }}</td><td>{{ $row->patient_name }}</td>
            <td>{{ $row->blood_group }}</td><td>{{ number_format($row->quantity) }}</td>
            <td>{{ $row->urgency_level }}</td><td>{{ $row->request_date?->format('d M Y') ?? '—' }}</td><td>{{ $row->status }}</td></tr>
        @empty<tr><td colspan="8" style="text-align:center;color:#888;">No data.</td></tr>@endforelse
    </tbody>
</table>
@elseif($type === 'distributions')
<table>
    <thead><tr><th>#</th><th>Hospital</th><th>Blood Group</th><th>Qty (ml)</th><th>Date</th><th>Approved By</th></tr></thead>
    <tbody>
        @forelse($data as $i => $row)
        <tr><td>{{ $i+1 }}</td><td>{{ $row->hospital->hospital_name ?? 'N/A' }}</td><td>{{ $row->blood_group }}</td>
            <td>{{ number_format($row->quantity) }}</td><td>{{ $row->distribution_date?->format('d M Y') ?? '—' }}</td>
            <td>{{ $row->approvedBy->name ?? '—' }}</td></tr>
        @empty<tr><td colspan="6" style="text-align:center;color:#888;">No data.</td></tr>@endforelse
        <tr style="background:#f8f8f8;font-weight:700;"><td colspan="3">Total</td><td>{{ number_format($data->sum('quantity')) }} ml</td><td colspan="2"></td></tr>
    </tbody>
</table>
@elseif($type === 'inventory')
<table>
    <thead><tr><th>Blood Group</th><th>Quantity (ml)</th><th>Status</th><th>Last Updated</th></tr></thead>
    <tbody>
        @forelse($data as $row)
        <tr><td>{{ $row->blood_group }}</td><td>{{ number_format($row->quantity) }}</td>
            <td>{{ $row->quantity < 500 ? 'Critical' : ($row->quantity < 1000 ? 'Low' : 'Sufficient') }}</td>
            <td>{{ $row->updated_at->format('d M Y') }}</td></tr>
        @empty<tr><td colspan="4" style="text-align:center;color:#888;">No data.</td></tr>@endforelse
        <tr style="background:#f8f8f8;font-weight:700;"><td>TOTAL</td><td>{{ number_format($data->sum('quantity')) }} ml</td><td colspan="2"></td></tr>
    </tbody>
</table>
@elseif($type === 'donors')
<table>
    <thead><tr><th>#</th><th>Name</th><th>Blood Group</th><th>Gender</th><th>Status</th><th>Last Donation</th><th>Count</th></tr></thead>
    <tbody>
        @forelse($data as $i => $row)
        <tr><td>{{ $i+1 }}</td><td>{{ $row->full_name }}</td><td>{{ $row->blood_group }}</td>
            <td>{{ $row->gender }}</td><td>{{ $row->status }}</td>
            <td>{{ $row->last_donation_date?->format('d M Y') ?? '—' }}</td>
            <td>{{ $row->bloodCollections->count() }}</td></tr>
        @empty<tr><td colspan="7" style="text-align:center;color:#888;">No data.</td></tr>@endforelse
    </tbody>
</table>
@endif

<div class="footer">
    <strong>BloodBank Management System</strong> — Printed on {{ now()->format('d M Y H:i') }}
    — Total records: {{ $data->count() }}
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
