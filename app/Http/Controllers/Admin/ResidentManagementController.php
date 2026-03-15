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
	 * Approve a resident registration.
	 */
	public function approve(Request $request, User $user)
	{
		$user->status = 'approved';
		$user->save();
		// Optionally notify the user
		if (class_exists('App\\Notifications\\UserRegistrationApproved')) {
			$user->notify(new \App\Notifications\UserRegistrationApproved($user));
		}
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Approve Resident',
			'description' => "Approved resident registration for user ID: {$user->id}",
		]);
		return redirect()->route('admin.users.show', $user->id)
			->with('success', 'Resident approved successfully.');
	}

	/**
	 * Display a listing of the residents for admin management.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request)
	{
		// Fetch residents with optional filters (status, gender, search)
		$query = User::withTrashed()->where('role', 'resident');

		if ($request->filled('status')) {
			if (strtolower($request->status) === 'archived') {
				$query = User::onlyTrashed()->where('role', 'resident');
			} else {
				$query->where('status', $request->status);
			}
		}

		if ($request->filled('gender')) {
			$query->where('gender', $request->gender);
		}

		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function($q) use ($search) {
				$q->where('first_name', 'like', "%$search%")
				  ->orWhere('last_name', 'like', "%$search%")
				  ->orWhere('email', 'like', "%$search%")
				  ->orWhere('address', 'like', "%$search%")
				  ->orWhere('contact_number', 'like', "%$search%")
				  ->orWhere('username', 'like', "%$search%")
				  ;
			});
		}

		$users = $query->latest()->paginate(100);

		// Statistics for dashboard/cards
		$totalResidents = User::whereIn('role', ['user', 'resident'])
			->where('status', 'approved')
			->whereNull('deleted_at')
			->count();
		$maleCount = User::where('role', 'resident')->where('gender', 'Male')->where('status', 'approved')->count();
		$femaleCount = User::where('role', 'resident')->where('gender', 'Female')->where('status', 'approved')->count();
		$archivedCount = User::where('role', 'resident')->onlyTrashed()->count();
		$registeredResidents = User::whereIn('role', ['user', 'resident'])
			->where('status', 'approved')
			->whereNull('deleted_at')
			->count();

		return view('admin.users.index', compact(
			'users',
			'totalResidents',
			'maleCount',
			'femaleCount',
			'archivedCount',
			'registeredResidents'
		));
	}
	public function setPassword(Request $request, User $user)
	{
		$request->validate([
			'password' => ['required', 'string', 'min:6', 'confirmed'],
		]);
		$user->password = bcrypt($request->password);
		$user->save();
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Set Resident Password',
			'description' => "Set a new password for resident ID: {$user->id}",
		]);
		return redirect()->route('admin.users.show', $user->id)->with('success', 'Password updated successfully.');
	}

	/**
	 * Permanently delete a user.
	 */
	public function destroy(User $user)
	{
		$user->delete();
		return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
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
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Edit Resident Information',
			'description' => "Updated a resident’s personal information",
		]);
		return redirect()->route('admin.users.show', $user->id)
			->with('success', 'User information updated successfully.');
	}

}