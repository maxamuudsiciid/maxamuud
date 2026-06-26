<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;

class AppointmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'hospital') {
            abort(403, 'Hospitals do not have access to the Appointment Booking system.');
        }

        if ($user->role === 'donor') {
            if (!$user->donor) {
                $appointments = collect();
            } else {
                $appointments = Appointment::where('donor_id', $user->donor->id)
                    ->latest('appointment_date')
                    ->latest('appointment_time')
                    ->paginate(10);
            }
        } else {
            $appointments = Appointment::with('donor')
                ->latest('appointment_date')
                ->latest('appointment_time')
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        if (Auth::user()->role === 'hospital') {
            abort(403, 'Hospitals cannot book appointments.');
        }

        return view('appointments.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'hospital') {
            abort(403, 'Hospitals cannot book appointments.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $exists = Appointment::where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'appointment_time' => 'This time slot is already booked.'
            ])->withInput();
        }

        $donor_id = null;

        if (Auth::user()->role === 'donor') {
            if (!Auth::user()->donor) {
                return back()->with('error', 'You must complete your donor profile first.');
            }

            $donor_id = Auth::user()->donor->id;
        } else {
            $request->validate([
                'donor_id' => 'required|exists:donors,id'
            ]);

            $donor_id = $request->donor_id;
        }

        Appointment::create([
            'donor_id' => $donor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    public function edit(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->role === 'hospital') {
            abort(403, 'Hospitals cannot edit appointments.');
        }

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->role === 'hospital') {
            abort(403, 'Hospitals cannot edit appointments.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        if (Auth::user()->role === 'hospital') {
            abort(403, 'Hospitals cannot delete appointments.');
        }

        $appointment->delete(61);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Completed',
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Appointment status updated successfully.'
        );
    }
}