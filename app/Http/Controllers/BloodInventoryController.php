<?php

namespace App\Http\Controllers;

use App\Models\BloodInventory;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    public function index()
    {
        $inventory = BloodInventory::orderBy('blood_group')->get();
        return view('blood-inventory.index', compact('inventory'));
    }

    public function readOnlyIndex()
    {
        $inventory = BloodInventory::orderBy('blood_group')->get();
        return view('blood-inventory.view', compact('inventory'));
    }

    public function create()
    {
        return view('blood-inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_group' => 'required|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity'    => 'required|integer|min:1',
        ]);

        $inventory = BloodInventory::firstOrCreate(
            ['blood_group' => $request->blood_group],
            ['quantity'    => 0]
        );
        $inventory->quantity += $request->quantity;
        $inventory->save();

        return redirect()->route('blood-inventory.index')
            ->with('success', "Added {$request->quantity}ml to {$request->blood_group} inventory.");
    }

    public function show(BloodInventory $blood_inventory)
    {
        return redirect()->route('blood-inventory.index');
    }

    public function edit(BloodInventory $blood_inventory)
    {
        return view('blood-inventory.edit', ['inventory' => $blood_inventory]);
    }

    public function update(Request $request, BloodInventory $blood_inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $blood_inventory->update(['quantity' => $request->quantity]);

        return redirect()->route('blood-inventory.index')
            ->with('success', "Inventory for {$blood_inventory->blood_group} updated to {$request->quantity}ml.");
    }

    public function destroy(BloodInventory $blood_inventory)
    {
        $blood_inventory->delete();
        return redirect()->route('blood-inventory.index')
            ->with('success', "Inventory record for {$blood_inventory->blood_group} deleted.");
    }
}
