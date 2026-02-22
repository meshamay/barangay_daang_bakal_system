<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users and statistics for the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalResidents = User::whereIn('role', ['user', 'resident'])
                            ->where('status', 'approved')
                            ->whereNull('deleted_at')
                            ->count();
        $maleCount      = User::where('role', 'resident')->where('gender', 'Male')->where('status', 'approved')->count();
        $femaleCount    = User::where('role', 'resident')->where('gender', 'Female')->where('status', 'approved')->count();
        $archivedCount  = User::where('role', 'resident')->onlyTrashed()->count();
        $registeredResidents = User::whereRaw('LOWER(role) IN (?, ?)', ['user', 'resident'])
            ->whereRaw('LOWER(status) = ?', ['approved'])
            ->whereNull('deleted_at')
            ->count();


        // DEBUG: Dump users being counted as registered residents
        // Remove after debugging
        $debugResidents = User::whereIn('role', ['user', 'resident'])
            ->where('status', 'approved')
            ->whereNull('deleted_at')
            ->get(['id', 'role', 'status', 'deleted_at']);



        // Always start with all users including archived
        $query = User::withTrashed()->where('role', 'resident');

        // Filter by status
        if (request('status')) {
            if (request('status') === 'Archived') {
                $query = User::onlyTrashed()->where('role', 'user');
            } else {
                $query = User::where('role', 'user')->where('status', request('status'));
            }
        }

        // Filter by gender
        if (request('gender')) {
            $query->where('gender', request('gender'));
        }

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('contact_number', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ;
            });
        }

        $users = $query->latest()->paginate(100);

        return view('admin.users.index', compact(
            'users',
            'totalResidents',
            'maleCount',
            'femaleCount',
            'archivedCount',
            'registeredResidents'
        ));
    }

    /**
     * Display the specified user's details.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Approve a user's registration.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
        return redirect()->route('admin.users.index')->with('success', 'User approved successfully.');
    }

    /**
     * Archive a user (soft delete).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(User $user)
    {
        $user->delete(); 
        return redirect()->route('admin.users.index')->with('success', 'User archived successfully.');
    }

    /**
     * Permanently delete a user.
     * Note: This method is now named archive() to match your previous logic.
     * If you want a permanent delete, you can create a new method for it.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->forceDelete(); // This will permanently delete the user.
        return redirect()->route('admin.users.index')->with('success', 'User permanently deleted successfully.');
    }

    /**
     * Restore an archived user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect()->route('admin.users.index')->with('success', 'User restored successfully.');
    }
}