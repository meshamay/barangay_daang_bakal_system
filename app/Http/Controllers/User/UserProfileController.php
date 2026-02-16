<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Show the User Profile page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('user.user-profile.index', compact('user'));
    }

    /**
     * Update the User Profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'contact_number' => 'required|string|max:15',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'address'        => 'required|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }
}