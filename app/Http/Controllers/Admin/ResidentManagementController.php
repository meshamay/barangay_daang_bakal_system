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
	 * Reject a resident registration.
	 */
	public function reject(Request $request, User $user)
	{
		$reason = $request->input('reason', null);
		$user->status = 'reject';
		$user->save();
		// Optionally notify the user
		if (class_exists('App\\Notifications\\UserRegistrationRejected')) {
			$user->notify(new \App\Notifications\UserRegistrationRejected($user, $reason));
		}
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Reject Resident Registration',
			'description' => "Rejected a resident’s registration",
		]);
		return redirect()->route('admin.users.index')->with('success', 'User rejected successfully.');
	}

	/**
	 * Archive (soft delete) a user/resident.
	 */
	public function archive(User $user)
	{
		$user->delete();
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Archive Resident Account',
			'description' => "Archived a resident’s account",
		]);
		return redirect()->route('admin.users.index')->with('success', 'User archived successfully.');
	}
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
			'action' => 'Accept Resident Registration',
			'description' => "Accepted a resident’s registration",
		]);
		return redirect()->route('admin.users.index')->with('success', 'Resident approved successfully.');
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
			$status = strtolower($request->status);
			if ($status === 'archived') {
				$query = User::onlyTrashed()->where('role', 'resident');
			} elseif ($status === 'reject' || $status === 'rejected') {
				$query->whereIn('status', ['reject', 'rejected']);
			} elseif ($status === 'approved') {
				$query->whereIn('status', ['approved', 'approve']);
				$query->whereNull('deleted_at');
			} else {
				$query->whereRaw('LOWER(status) = ?', [$status]);
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

		$users = $query->latest()->paginate(10);

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
			'password' => ['required', 'string', 'min:6'],
		]);
		$user->password = bcrypt($request->password);
		$user->save();
		\App\Models\AuditLog::create([
			'user_id' => auth()->id(),
			'action' => 'Reset Resident Password',
			'description' => "Reset a resident’s password",
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