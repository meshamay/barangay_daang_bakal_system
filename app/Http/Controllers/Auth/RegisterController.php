<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /**
     * Show the Registration View.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the AJAX Registration Request (All steps in one).
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'suffix'         => 'nullable|string|max:50',
            'gender'         => 'required|string',
            'age'            => 'required|integer|min:18',
            'civil_status'   => 'required|string',
            'dob'            => 'required|date',
            'place_of_birth' => 'required|string',
            'photo'          => 'required|image|max:2048', // 2MB Limit (PHP upload_max_filesize limit)

            'contact_number' => 'required|string|max:15',
            'email'          => 'nullable|email|unique:users,email',
            'address'        => 'required|string|max:255',
            'barangay'       => 'required|string|max:255',
            'city_municipality' => 'nullable|string|max:255',
            'username'       => 'required|string|unique:users,username',
            'password'       => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'id_front'       => 'required|image|max:2048',
            'id_back'        => 'required|image|max:2048',
            'agree'          => 'required|accepted',
        ], [
            'email.unique'          => 'This email address is already registered.',
            'username.unique'       => 'This username is already taken. Please choose a different one.',
            'password.min'          => 'Password must be at least 8 characters long.',
            'password.mixed'        => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers'      => 'Password must contain at least one number.',
            'password.symbols'      => 'Password must contain at least one special character (e.g. @, #, $, !).',
            'password.confirmed'    => 'Password and confirm password do not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $photoPath = null;
        $idFrontPath = null;
        $idBackPath = null;

        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('uploads/profile_photos', 'public');
            }
            if ($request->hasFile('id_front')) {
                $idFrontPath = $request->file('id_front')->store('uploads/ids', 'public');
            }
            if ($request->hasFile('id_back')) {
                $idBackPath = $request->file('id_back')->store('uploads/ids', 'public');
            }

           
            $existingIds = User::withTrashed()
                ->where('resident_id', 'like', 'RS-%')
                ->pluck('resident_id');

            $maxNumber = 0;

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
            
            $data = $request->only([
                'first_name', 'last_name', 'middle_name', 'suffix', 'gender', 'age', 
                'civil_status', 'citizenship', 'place_of_birth', 'contact_number', 
                'email', 'address', 'barangay', 'city_municipality', 'username'
            ]);

            $data['middle_name'] = $request->filled('middle_name') ? $request->middle_name : null;
            $data['suffix']      = $request->filled('suffix') ? $request->suffix : null;
            $data['birthdate']   = $request->dob;
            
            if (!isset($data['citizenship'])) {
                $data['citizenship'] = 'Filipino'; 
            }

            // Force user_type and role to resident, prevent superadmin creation
            $user = User::create([
                'resident_id'     => $residentId,
                'password'        => Hash::make($request->password),
                'photo_path'      => $photoPath,
                'id_front_path'   => $idFrontPath,
                'id_back_path'    => $idBackPath,
                'user_type'       => 'resident',
                'role'            => 'resident',
                'status'          => 'pending',
                ...array_diff_key($data, array_flip(['user_type', 'role'])),
            ]);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Registration Failed: ' . $e->getMessage());

            if ($photoPath && Storage::disk('public')->exists($photoPath)) Storage::disk('public')->delete($photoPath);
            if ($idFrontPath && Storage::disk('public')->exists($idFrontPath)) Storage::disk('public')->delete($idFrontPath);
            if ($idBackPath && Storage::disk('public')->exists($idBackPath)) Storage::disk('public')->delete($idBackPath);

            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}