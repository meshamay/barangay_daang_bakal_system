<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegistrationForm() { return view('auth.register'); }
    public function showLoginForm() { return view('auth.login'); }

    public function register(Request $request) {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'photo' => 'required|image|max:5120',
            'gender' => 'required|string',
            'age' => 'required|integer',
            'civil_status' => 'required|string',
            'dob' => 'required|date',
            'place_of_birth' => 'required|string',
            'contact_number' => 'required|string|max:10',
            'id_front' => 'required|image|max:5120',
            'id_back' => 'required|image|max:5120',
            'address' => 'required|string',
            'barangay' => 'required|string|max:255',
            'city_municipality' => 'nullable|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'agree' => 'required',
        ]);
        
        $user = User::create([
            'last_name' => $request->last_name, 'first_name' => $request->first_name,
            'middle_name' => $request->middle_name, 'suffix' => $request->suffix,
            'photo_path' => $request->file('photo')->store('user_photos', 'public'),
            'gender' => $request->gender, 'age' => $request->age,
            'civil_status' => $request->civil_status, 'dob' => $request->dob,
            'citizenship' => $request->citizenship, 'place_of_birth' => $request->place_of_birth,
            'contact_number' => $request->contact_number, 'email' => $request->email,
            'id_front_path' => $request->file('id_front')->store('user_ids', 'public'),
            'id_back_path' => $request->file('id_back')->store('user_ids', 'public'),
            'address' => $request->address,
            'barangay' => $request->barangay,
            'city_municipality' => $request->city_municipality,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function login(Request $request) {
        $credentials = $request->validate(['username' => 'required', 'password' => 'required']);
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']]) ||
            Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}