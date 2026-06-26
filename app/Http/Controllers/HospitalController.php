<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HospitalController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'hospital') {
            $hospital = auth()->user()->hospital;
            if ($hospital) {
                return redirect()->route('hospitals.edit', $hospital->id);
            }
            return redirect()->route('dashboard')->with('error', 'Hospital profile not found.');
        }

        $hospitals = Hospital::latest()->paginate(10);
        return view('hospitals.index', compact('hospitals'));
    }

    public function show(Hospital $hospital)
    {
        $hospital->load('bloodRequests');
        return view('hospitals.show', compact('hospital'));
    }

    public function create()
    {
        return view('hospitals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:hospitals,email|unique:users,email',
            'contact_person' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->hospital_name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => 'hospital',
        ]);

        $hospital = new Hospital($request->all());
        $hospital->user_id = $user->id;
        $hospital->save();

        return redirect()->route('hospitals.index')->with('success', 'Hospital added successfully.');
    }

    public function edit(Hospital $hospital)
    {
        if (auth()->user()->role === 'hospital' && auth()->user()->hospital->id !== $hospital->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        if (auth()->user()->role === 'hospital' && auth()->user()->hospital->id !== $hospital->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'hospital_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
        ]);

        $hospital->update($request->except(['email']));

        if (auth()->user()->role === 'hospital') {
            return redirect()->route('hospitals.edit', $hospital->id)->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('hospitals.index')->with('success', 'Hospital updated successfully.');
    }

    public function destroy(Hospital $hospital)
    {
        if ($hospital->user) {
            $hospital->user->delete();
        }
        $hospital->delete();
        return redirect()->route('hospitals.index')->with('success', 'Hospital deleted successfully.');
    }
}
