<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role === 'donor') {
            $donor = auth()->user()->donor;
            if ($donor) {
                return redirect()->route('donors.show', $donor->id);
            }
            return redirect()->route('dashboard')->with('error', 'Donor profile not found.');
        }

        $query = Donor::query();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donors = $query->orderBy('full_name')->paginate(15);
        return view('donors.index', compact('donors'));
    }

    public function create()
    {
        return view('donors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'blood_group' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:donors,email|unique:users,email',
            'address' => 'required|string',
            'last_donation_date' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make('password'), // default password
            'role' => 'donor',
        ]);

        $donor = new Donor($request->all());
        $donor->user_id = $user->id;
        $donor->save();

        return redirect()->route('donors.index')->with('success', 'Donor added successfully.');
    }

    public function show(Donor $donor)
    {
        return view('donors.show', compact('donor'));
    }

    public function edit(Donor $donor)
    {
        if (auth()->user()->role === 'donor' && auth()->user()->donor->id !== $donor->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('donors.edit', compact('donor'));
    }

    public function update(Request $request, Donor $donor)
    {
        if (auth()->user()->role === 'donor' && auth()->user()->donor->id !== $donor->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'blood_group' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $data = $request->except(['status']);
        if (auth()->user()->role !== 'donor') {
            $request->validate(['status' => 'required|in:Active,Inactive,Deferred']);
            $data['status'] = $request->status;
        }

        $donor->update($data);

        if (auth()->user()->role === 'donor') {
            return redirect()->route('donors.show', $donor->id)->with('success', 'Profile updated successfully.');
        }
        return redirect()->route('donors.index')->with('success', 'Donor updated successfully.');
    }

    public function destroy(Donor $donor)
    {
        if ($donor->user) {
            $donor->user->delete();
        }
        $donor->delete();
        return redirect()->route('donors.index')->with('success', 'Donor deleted successfully.');
    }
}
