<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserRegistrationApproved;
use App\Notifications\UserRegistrationRejected;
use Illuminate\Http\Request;

class ResidentManagementController extends Controller
{
    /**
     * Display the list of residents with filtering and statistics.
     */
    public function index(Request $request)
    {
        
        $totalResidents = User::where('user_type', 'resident')
            ->withTrashed()
            ->count();
        $maleCount      = User::where('user_type', 'resident')
            ->withTrashed()
            ->whereRaw('LOWER(TRIM(gender)) = ?', ['male'])
            ->count();
        $femaleCount    = User::where('user_type', 'resident')
            ->withTrashed()
            ->whereRaw('LOWER(TRIM(gender)) = ?', ['female'])
            ->count();
        $archivedCount  = User::where('user_type', 'resident')->onlyTrashed()->count();
        $femaleResidents = User::where('user_type', 'resident')
            ->withTrashed()
            ->whereRaw('LOWER(TRIM(gender)) = ?', ['female'])
            ->get();

        $query = User::where('user_type', 'resident')->withTrashed();

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('resident_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('gender') && $request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('status') && $request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }


        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'totalResidents',
            'maleCount',
            'femaleCount',
            'archivedCount',
            'femaleResidents'
        ));
    }

    /**
     * Display the specified user profile (The "View" Button).
     */
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'age' => 'required|integer|min:1|max:150',
            'birthdate' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'civil_status' => 'required|string|max:50',
            'citizenship' => 'required|string|max:100',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User information updated successfully.');
    }

    /**
     * Accept a user (Fixes the BadMethodCallException).
     * This method handles the route: /admin/users/{id}/accept
     */
    public function accept(User $user)
    {
        if ($user->trashed()) {
            $user->restore();
        }
        $user->update(['status' => 'approved']); // or 'active' depending on your logic
        $user->notify(new UserRegistrationApproved($user));
        return back()->with('success', 'User accepted successfully. Approval email sent.');
    }

    /**
     * Approve a user.
     */
    public function approve(User $user)
    {
        if ($user->trashed()) {
            $user->restore();
        }
        $user->update(['status' => 'approved']);
        $user->notify(new UserRegistrationApproved($user));
        return back()->with('success', 'User approved successfully. Approval email sent.');
    }

    /**
     * Reject a user.
     */
    public function reject(Request $request, User $user)
    {
        $reason = $request->input('reason');
        $user->update(['status' => 'reject']);
        $user->notify(new UserRegistrationRejected($user, $reason));
        return back()->with('success', 'User registration rejected. Rejection email sent.');
    }

    /**
     * Archive a user (soft delete).
     */
    public function archive(User $user)
    {
        $user->delete();
        return back()->with('success', 'User archived successfully.');
    }

    /**
     * Restore an archived user.
     */
    public function restore(User $user)
    {
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}