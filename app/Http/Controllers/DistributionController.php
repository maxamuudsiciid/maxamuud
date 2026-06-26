<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Distribution;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = Distribution::with(['bloodRequest', 'hospital', 'approvedBy'])
            ->latest()->paginate(10);
        return view('distributions.index', compact('distributions'));
    }

    public function create(Request $request)
    {
        $bloodRequestId = $request->get('request_id');
        $bloodRequest   = null;

        if ($bloodRequestId) {
            $bloodRequest = BloodRequest::with('hospital')->findOrFail($bloodRequestId);
        }

        $requests = BloodRequest::where('status', 'Approved')->with('hospital')->get();
        return view('distributions.create', compact('requests', 'bloodRequest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'distribution_date' => 'required|date',
        ]);

        $bloodRequest = BloodRequest::findOrFail($request->blood_request_id);

        if ($bloodRequest->status === 'Fulfilled') {
            return back()->with('error', 'This request is already fulfilled.');
        }

        $inventory = BloodInventory::where('blood_group', $bloodRequest->blood_group)->first();

        if (!$inventory || $inventory->quantity < $bloodRequest->quantity) {
            $available = $inventory ? $inventory->quantity : 0;
            return back()->with('error', "Insufficient {$bloodRequest->blood_group} stock. Available: {$available}ml, Required: {$bloodRequest->quantity}ml.");
        }

        DB::transaction(function () use ($bloodRequest, $request, $inventory) {
            Distribution::create([
                'blood_request_id'  => $bloodRequest->id,
                'hospital_id'       => $bloodRequest->hospital_id,
                'blood_group'       => $bloodRequest->blood_group,
                'quantity'          => $bloodRequest->quantity,
                'distribution_date' => $request->distribution_date,
                'approved_by'       => Auth::id(),
            ]);

            $inventory->decrement('quantity', $bloodRequest->quantity);
            $bloodRequest->update(['status' => 'Fulfilled']);

            if ($inventory->quantity < 1000) {
                SystemNotification::create([
                    'user_id' => null,
                    'title'   => 'Low Stock Alert',
                    'message' => "Blood group {$inventory->blood_group} is low: {$inventory->quantity}ml remaining (below 1000ml threshold).",
                ]);
            }
        });

        return redirect()->route('distributions.index')
            ->with('success', 'Blood distributed successfully. Inventory updated.');
    }

    public function show(Distribution $distribution)
    {
        $distribution->load(['bloodRequest', 'hospital', 'approvedBy']);
        return view('distributions.show', compact('distribution'));
    }

    public function edit(Distribution $distribution)
    {
        // Distributions are immutable (no edit). Redirect to show.
        return redirect()->route('distributions.show', $distribution->id)
            ->with('warning', 'Distribution records cannot be edited after creation.');
    }

    public function update(Request $request, Distribution $distribution)
    {
        return redirect()->route('distributions.show', $distribution->id)
            ->with('warning', 'Distribution records cannot be edited after creation.');
    }

    public function destroy(Distribution $distribution)
    {
        $distribution->delete();
        return redirect()->route('distributions.index')
            ->with('success', 'Distribution record deleted.');
    }
}
