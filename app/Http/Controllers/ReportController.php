<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodCollection;
use App\Models\BloodRequest;
use App\Models\BloodInventory;
use App\Models\Distribution;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'collections');
        $from = $request->get('from');
        $to   = $request->get('to');

        // Summary totals
        $totalDonors        = Donor::count();
        $totalCollections   = BloodCollection::count();
        $totalRequests      = BloodRequest::count();
        $totalDistributions = Distribution::count();

        $data = collect();

        switch ($type) {
            case 'collections':
                $query = BloodCollection::with('donor');
                if ($from) $query->whereDate('donation_date', '>=', $from);
                if ($to)   $query->whereDate('donation_date', '<=', $to);
                $data = $query->orderBy('donation_date', 'desc')->get();
                break;

            case 'requests':
                $query = BloodRequest::with('hospital');
                if ($from) $query->whereDate('request_date', '>=', $from);
                if ($to)   $query->whereDate('request_date', '<=', $to);
                $data = $query->orderBy('request_date', 'desc')->get();
                break;

            case 'distributions':
                $query = Distribution::with(['hospital', 'approvedBy']);
                if ($from) $query->whereDate('distribution_date', '>=', $from);
                if ($to)   $query->whereDate('distribution_date', '<=', $to);
                $data = $query->orderBy('distribution_date', 'desc')->get();
                break;

            case 'inventory':
                $data = BloodInventory::orderBy('blood_group')->get();
                break;

            case 'donors':
                $query = Donor::with('bloodCollections');
                if ($from) $query->whereDate('created_at', '>=', $from);
                if ($to)   $query->whereDate('created_at', '<=', $to);
                $data = $query->orderBy('full_name')->get();
                break;
        }

        return view('reports.index', compact(
            'data', 'totalDonors', 'totalCollections',
            'totalRequests', 'totalDistributions'
        ));
    }

    public function print(Request $request)
    {
        $type = $request->get('type', 'collections');
        $from = $request->get('from');
        $to   = $request->get('to');

        $data = collect();

        switch ($type) {
            case 'collections':
                $query = BloodCollection::with('donor');
                if ($from) $query->whereDate('donation_date', '>=', $from);
                if ($to)   $query->whereDate('donation_date', '<=', $to);
                $data = $query->orderBy('donation_date', 'desc')->get();
                break;

            case 'requests':
                $query = BloodRequest::with('hospital');
                if ($from) $query->whereDate('request_date', '>=', $from);
                if ($to)   $query->whereDate('request_date', '<=', $to);
                $data = $query->orderBy('request_date', 'desc')->get();
                break;

            case 'distributions':
                $query = Distribution::with(['hospital', 'approvedBy']);
                if ($from) $query->whereDate('distribution_date', '>=', $from);
                if ($to)   $query->whereDate('distribution_date', '<=', $to);
                $data = $query->orderBy('distribution_date', 'desc')->get();
                break;

            case 'inventory':
                $data = BloodInventory::orderBy('blood_group')->get();
                break;

            case 'donors':
                $query = Donor::with('bloodCollections');
                if ($from) $query->whereDate('created_at', '>=', $from);
                if ($to)   $query->whereDate('created_at', '<=', $to);
                $data = $query->orderBy('full_name')->get();
                break;
        }

        return view('reports.print', compact('data'));
    }
}
