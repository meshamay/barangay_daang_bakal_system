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
        $totalResidents = User::where('role', 'user')->count();
        $maleCount      = User::where('role', 'user')->where('gender', 'Male')->count();
        $femaleCount    = User::where('role', 'user')->where('gender', 'Female')->count();
        $archivedCount  = User::where('role', 'user')->onlyTrashed()->count();


        
        $users = User::latest()->paginate(10); 


        
        return view('admin.users.index', compact(
            'users',
            'totalResidents',
            'maleCount',
            'femaleCount',
            'archivedCount'
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