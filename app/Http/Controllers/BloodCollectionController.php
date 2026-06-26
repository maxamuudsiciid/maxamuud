<?php

namespace App\Http\Controllers;

use App\Models\BloodCollection;
use App\Models\Donor;
use App\Models\BloodInventory;
use Illuminate\Http\Request;

class BloodCollectionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'donor') {
            $donor = auth()->user()->donor;
            if ($donor) {
                $collections = BloodCollection::with('donor')->where('donor_id', $donor->id)->latest()->paginate(10);
            } else {
                $collections = collect();
            }
        } else {
            $collections = BloodCollection::with('donor')->latest()->paginate(10);
        }
        return view('blood-collections.index', compact('collections'));
    }

    public function create()
    {
        $donors = Donor::where('status', 'Active')->get();
        return view('blood-collections.create', compact('donors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:donors,id',
            'quantity' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'screening_result' => 'required|in:Pending,Safe,Unsafe'
        ]);

        $donor = Donor::findOrFail($request->donor_id);
        
        $collection = new BloodCollection($request->all());
        $collection->blood_group = $donor->blood_group;
        $collection->expiry_date = \Carbon\Carbon::parse($request->donation_date)->addDays(42);
        $collection->save();

        if ($collection->screening_result == 'Safe') {
            $this->addToInventory($collection->blood_group, $collection->quantity);
        }

        return redirect()->route('blood-collections.index')->with('success', 'Blood collection recorded successfully.');
    }

    public function show(BloodCollection $blood_collection)
    {
        if (auth()->user()->role === 'donor') {
            if (!auth()->user()->donor || $blood_collection->donor_id !== auth()->user()->donor->id) {
                abort(403, 'Unauthorized access to this blood collection.');
            }
        }

        $blood_collection->load(['donor', 'bloodTest']);
        return view('blood-collections.show', ['collection' => $blood_collection]);
    }

    public function updateScreening(Request $request, BloodCollection $collection)
    {
        $request->validate([
            'screening_result' => 'required|in:Safe,Unsafe'
        ]);

        $collection->screening_result = $request->screening_result;
        $collection->save();

        if ($request->screening_result == 'Safe') {
            $this->addToInventory($collection->blood_group, $collection->quantity);
        }

        return back()->with('success', 'Screening result updated to ' . $request->screening_result . '.');
    }

    public function edit(BloodCollection $blood_collection)
    {
        $donors = Donor::where('status', 'Active')->get();
        return view('blood-collections.edit', ['collection' => $blood_collection, 'donors' => $donors]);
    }

    public function update(Request $request, BloodCollection $blood_collection)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'screening_result' => 'required|in:Pending,Safe,Unsafe'
        ]);

        $blood_collection->update($request->all());

        return redirect()->route('blood-collections.index')->with('success', 'Blood collection updated successfully.');
    }

    public function destroy(BloodCollection $blood_collection)
    {
        $blood_collection->delete();
        return redirect()->route('blood-collections.index')->with('success', 'Blood collection deleted successfully.');
    }

    private function addToInventory($blood_group, $quantity)
    {
        $inventory = BloodInventory::firstOrCreate(
            ['blood_group' => $blood_group],
            ['quantity' => 0]
        );
        $inventory->quantity += $quantity;
        $inventory->save();
    }
}
