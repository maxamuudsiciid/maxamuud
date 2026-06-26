<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($request->has('remove_image')) {
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists('profiles/' . $user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('profiles/' . $user->profile_photo);
            }
            $user->profile_photo = null;
        }

        if ($request->hasFile('image')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists('profiles/' . $user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('profiles/' . $user->profile_photo);
            }

            // Store new photo
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profiles', $filename, 'public');
            $user->profile_photo = $filename;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
