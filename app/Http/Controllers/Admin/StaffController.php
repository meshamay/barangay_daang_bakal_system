<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $allowed = ['superadmin', 'super admin', 'super_admin'];

            if (!in_array(strtolower($user->user_type ?? ''), $allowed) && !in_array(strtolower($user->role ?? ''), $allowed)) {
                abort(403, 'Unauthorized. Only superadmin can manage staff.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of admin staff.
     */
    public function index(Request $request)
    {
        $query = User::where('user_type', 'admin');

        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(function ($q) use ($term) {
                $q->where('username', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('first_name', 'like', "%{$term}%")
                  ->orWhere('last_name', 'like', "%{$term}%")
                  ->orWhere('resident_id', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            $status = strtolower($request->input('status'));
            if ($status === 'active') {
                $query->whereIn(DB::raw('LOWER(status)'), ['approved', 'active']);
            } elseif ($status === 'inactive') {
                $query->whereIn(DB::raw('LOWER(status)'), ['inactive', 'archived', 'reject', 'blocked', 'disabled', 'pending']);
            }
        }

        $staff = $query->orderBy('last_name')->get();
        return view('admin.staffs.index', compact('staff'));
    }

    /**
     * Show the form for creating a new admin staff.
     */
    public function create()
    {
        return view('admin.staffs.create');
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        try {
            // Always return JSON for API endpoint
            return response()->json([
                'id' => $staff->id,
                'first_name' => $staff->first_name,
                'last_name' => $staff->last_name,
                'username' => $staff->username,
                'email' => $staff->email,
                'role' => $staff->role,
                'status' => $staff->status,
                'created_at' => $staff->created_at,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Staff member not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ]);

        $rawRole = strtolower($request->role);
        $isSuperAdmin = in_array($rawRole, ['superadmin', 'super admin', 'super_admin']);
        $normalizedRole = $isSuperAdmin ? 'super admin' : 'admin';

        // Generate unique resident_id
        $maxNumber = 0;
        $existingIds = User::withTrashed()
            ->where('resident_id', 'like', 'RS-%')
            ->pluck('resident_id');

        foreach ($existingIds as $id) {
            $numberPart = substr($id, 3);
            if (ctype_digit($numberPart)) {
                $currentNumber = (int) $numberPart;
                if ($currentNumber > $maxNumber) {
                    $maxNumber = $currentNumber;
                }
            }
        }
        $nextNumber = $maxNumber + 1;
        $residentId = 'RS-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        User::create([
            'resident_id' => $residentId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'user_type' => $isSuperAdmin ? 'super admin' : 'admin',
            'role' => $normalizedRole,
            'status' => 'approved',
            'gender' => 'Male', 
            'age' => 25, 
            'civil_status' => 'Single', 
            'birthdate' => now()->subYears(25)->toDateString(), 
            'place_of_birth' => 'Unknown', 
            'contact_number' => '0000000000', 
            'address' => 'Admin Address', 
        ]);

        return redirect()->route('admin.staffs.index')->with('success', 'Admin staff created successfully.');
    }

    /**
     * Update the specified staff member's status.
     */
    public function update(Request $request, User $staff)
    {
        $request->validate([
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $staff->id,
            'password' => 'nullable|string|min:8',
        ]);

        try {
            $updateData = [];

            if ($request->filled('last_name')) {
                $updateData['last_name'] = $request->last_name;
            }
            if ($request->filled('first_name')) {
                $updateData['first_name'] = $request->first_name;
            }
            if ($request->filled('username')) {
                $updateData['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
                $updateData['plain_password'] = $request->password;
            }

            $staff->update($updateData);

            return redirect()->route('admin.staffs.index')->with('success', 'Staff member updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.staffs.index')->with('error', 'Failed to update staff member: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate a staff member (set status to inactive).
     */
    public function deactivate(User $staff)
    {
        try {
            $staff->update(['status' => 'inactive']);
            return redirect()->route('admin.staffs.index')->with('success', 'Staff member deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.staffs.index')->with('error', 'Failed to deactivate staff member: ' . $e->getMessage());
        }
    }
}
