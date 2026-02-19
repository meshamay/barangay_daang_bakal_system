<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;   // <--- FIX 1: Added Log
use Illuminate\Support\Facades\Auth;  // <--- FIX 2: Added Auth

class UserController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if (!$user || !in_array($user->user_type, ['admin', 'super admin'])) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        Log::info('Store Request Data:', $request->except(['password', 'photo', 'front_id_photo', 'back_id_photo']));
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|string|max:50',
            'dob' => 'required|date',
            'civil_status' => 'required|string|max:50',
            'place_of_birth' => 'required|string|max:255',
            'citizenship' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'front_id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'back_id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'user_type' => 'nullable|in:user,admin,super admin',
            'agree' => 'nullable|accepted',
        ], [
            'dob.required' => 'The date of birth field is required.',
            'photo.required' => 'A profile photo is required.',
            'front_id_photo.required' => 'The front ID photo is required.',
            'back_id_photo.required' => 'The back ID photo is required.',
            'password.regex' => 'Password must contain upper, lower, number, and special character.',
            'agree.accepted' => 'You must agree to the Privacy Policy.',
        ]);

        $validated['profile_photo'] = $request->file('photo')->store('user_photos', 'public');
        $validated['id_front_photo'] = $request->file('front_id_photo')->store('user_ids', 'public');
        $validated['id_back_photo'] = $request->file('back_id_photo')->store('user_ids', 'public');

        $validated['password'] = Hash::make($validated['password']);

        $validated['birthdate'] = $validated['dob'];
        unset($validated['dob']);

        $validated['registration_status'] = 'pending';
        $validated['user_type'] = $validated['user_type'] ?? 'user';

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        Log::info('Update Request Data:', $request->except(['password', 'photo', 'front_id_photo', 'back_id_photo']));

        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|string|max:50',
            'dob' => 'required|date',
            'civil_status' => 'required|string|max:50',
            'place_of_birth' => 'required|string|max:255',
            'citizenship' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => [
                'nullable', 'string', 'min:8', 'confirmed',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'front_id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'back_id_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'user_type' => 'required|in:user,admin,super admin',
        ], [
            'dob.required' => 'The date of birth field is required.',
            'password.regex' => 'Password must contain upper, lower, number, and special character.',
        ]);

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($user->profile_photo);
            $validated['profile_photo'] = $request->file('photo')->store('user_photos', 'public');
        }

        if ($request->hasFile('front_id_photo')) {
            Storage::disk('public')->delete($user->id_front_photo);
            $validated['id_front_photo'] = $request->file('front_id_photo')->store('user_ids', 'public');
        }

        if ($request->hasFile('back_id_photo')) {
            Storage::disk('public')->delete($user->id_back_photo);
            $validated['id_back_photo'] = $request->file('back_id_photo')->store('user_ids', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['birthdate'] = $validated['dob'];
        unset($validated['dob']);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        Storage::disk('public')->delete([$user->profile_photo, $user->id_front_photo, $user->id_back_photo]);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index')->with('success', 'User restored successfully!');
    }


    public function approve($id)
    {
        return $this->setStatus($id, 'approved', 'User registration approved.');
    }

    public function inProgress($id)
    {
        return $this->setStatus($id, 'in progress', 'User marked as in progress.');
    }

    public function decline($id)
    {
        return $this->setStatus($id, 'declined', 'User registration declined.');
    }

    public function archive($id)
    {
        return $this->setStatus($id, 'archived', 'User archived.');
    }

    private function setStatus($id, $status, $message)
    {
        $user = User::findOrFail($id);
        $user->update(['registration_status' => $status]);

        return redirect()->back()->with('success', $message);
    }
}
