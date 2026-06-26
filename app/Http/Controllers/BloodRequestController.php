<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'hospital') {
            $hospital = auth()->user()->hospital;
            if ($hospital) {
                $requests = BloodRequest::with('hospital')->where('hospital_id', $hospital->id)->latest()->paginate(10);
            } else {
                $requests = collect(); // empty if no hospital profile
            }
        } else {
            $requests = BloodRequest::with('hospital')->latest()->paginate(10);
        }
        return view('blood-requests.index', ['requests' => $requests]);
    }

    public function show(BloodRequest $blood_request)
    {
        if (auth()->user()->role === 'hospital') {
            if (!auth()->user()->hospital || $blood_request->hospital_id !== auth()->user()->hospital->id) {
                abort(403, 'Unauthorized access to this blood request.');
            }
        }
        return view('blood-requests.show', ['request' => $blood_request]);
    }

    public function create()
    {
        if (auth()->user()->role === 'hospital') {
            // Only pass their own hospital to the view
            $hospital = auth()->user()->hospital;
            $hospitals = $hospital ? collect([$hospital]) : collect();
        } else {
            $hospitals = Hospital::all();
        }
        return view('blood-requests.create', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'patient_name' => 'required|string',
            'blood_group' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'urgency_level' => 'required|in:Normal,Urgent,Emergency',
            'request_date' => 'required|date'
        ]);

        $data = $request->all();
        if (auth()->user()->role === 'hospital') {
            if (!auth()->user()->hospital) {
                abort(403, 'No hospital profile linked to your account.');
            }
            $data['hospital_id'] = auth()->user()->hospital->id; // Enforce their own ID
        }

        $bloodRequest = new BloodRequest($data);
        $bloodRequest->status = 'Pending';
        $bloodRequest->save();

        return redirect()->route('blood-requests.index')->with('success', 'Blood request submitted successfully.');
    }

    public function updateStatus(Request $request, BloodRequest $blood_request)
    {
        if (auth()->user()->role === 'hospital') {
            abort(403, 'Hospitals cannot update request status.');
        }

        $request->validate([
            'status' => 'required|in:Approved,Rejected'
        ]);

        $blood_request->status = $request->status;
        $blood_request->save();

        return back()->with('success', 'Request status updated to ' . $request->status . '.');
    }

    public function edit(BloodRequest $blood_request)
    {
        if (auth()->user()->role === 'hospital') {
            if (!auth()->user()->hospital || $blood_request->hospital_id !== auth()->user()->hospital->id) {
                abort(403, 'Unauthorized access to this blood request.');
            }
            $hospitals = collect([auth()->user()->hospital]);
        } else {
            $hospitals = Hospital::all();
        }
        return view('blood-requests.edit', ['req' => $blood_request, 'hospitals' => $hospitals]);
    }

    public function update(Request $request, BloodRequest $blood_request)
    {
        $request->validate([
            'patient_name' => 'required|string',
            'blood_group' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'urgency_level' => 'required|in:Normal,Urgent,Emergency',
            'request_date' => 'required|date',
            'status' => 'required|in:Pending,Approved,Rejected,Fulfilled'
        ]);

        if (auth()->user()->role === 'hospital') {
            if (!auth()->user()->hospital || $blood_request->hospital_id !== auth()->user()->hospital->id) {
                abort(403, 'Unauthorized access to this blood request.');
            }
        }

        $updateData = $request->except(['hospital_id']);
        if (auth()->user()->role === 'hospital') {
            unset($updateData['status']);
        }
        $blood_request->update($updateData);

        return redirect()->route('blood-requests.index')->with('success', 'Blood request updated successfully.');
    }

    public function destroy(BloodRequest $blood_request)
    {
        if (auth()->user()->role === 'hospital') {
            if (!auth()->user()->hospital || $blood_request->hospital_id !== auth()->user()->hospital->id) {
                abort(403, 'Unauthorized access to this blood request.');
            }
        }
        $blood_request->delete();
        return redirect()->route('blood-requests.index')->with('success', 'Blood request deleted successfully.');
    }
}
