<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Show profile page
    public function index()
    {
        $user = auth()->user();

        // Get habit statistics
        $totalHabits  = $user->habits()->count();
        $activeHabits = $user->habits()->where('status', 'active')->count();
        $pausedHabits = $user->habits()->where('status', 'paused')->count();

        return view('profile.index', [
            'user'         => $user,
            'totalHabits'  => $totalHabits,
            'activeHabits' => $activeHabits,
            'pausedHabits' => $pausedHabits,
        ]);
    }

    // Show edit form
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', ['user' => $user]);
    }

    // Update profile
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'            => 'required|min:3',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Handle profile picture upload — store as base64 so it persists on Railway
        if ($request->hasFile('profile_picture')) {
            $file     = $request->file('profile_picture');
            $mime     = $file->getMimeType();
            $data     = base64_encode(file_get_contents($file->getRealPath()));
            $user->profile_picture = 'data:' . $mime . ';base64,' . $data;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('/profile')->with('success', 'Profile updated successfully!');
    }
}
