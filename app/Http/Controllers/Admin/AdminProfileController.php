<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Show the Admin Profile page.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update the Admin Profile.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $validated = $request->validate([
            'contact_number' => 'nullable|string|max:15',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'address'        => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }
}
