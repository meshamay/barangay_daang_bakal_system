<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\AdminLoginNotification;
use App\Notifications\UserLoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Find user to decide which guard to use
        $candidate = User::where($loginType, $credentials['username'])->first();
        $adminRoles = ['admin', 'super admin'];
        $shouldUseAdminGuard = $candidate && (
            in_array(strtolower($candidate->user_type ?? ''), array_map('strtolower', $adminRoles)) ||
            in_array(strtolower($candidate->role ?? ''), array_map('strtolower', $adminRoles))
        );

        $guard = $shouldUseAdminGuard ? Auth::guard('admin') : Auth::guard();

        if ($guard->attempt([$loginType => $credentials['username'], 'password' => $credentials['password']])) {

            $user = $guard->user();

            
            $isAdmin = $shouldUseAdminGuard;

           
            if (!$isAdmin && $user->status !== 'approved' && $user->status !== 'Active') {
                
                $guard->logout();

                $errorMessage = match ($user->status) {
                    'pending' => 'Your account is pending admin approval. You cannot log in yet.',
                    'reject', 'rejected'  => 'Your registration was rejected. Please contact the administrator.',
                    'archived' => 'This account has been archived.',
                    default   => 'This account is not active (Status: ' . $user->status . '). Please contact an administrator.'
                };

                return back()->withErrors(['username' => $errorMessage])->onlyInput('username');
            }


            // Regenerate the appropriate session for the guard used
            if ($isAdmin) {
                $request->session()->regenerate();
            } else {
                $request->session()->regenerate();
            }

            // Notify on the same guard's user instance to avoid null
            if ($isAdmin) {
                $user->notify(new AdminLoginNotification());
            } else {
                $user->notify(new UserLoginNotification());
            }

            // Create audit log for login
            $isResident = !$isAdmin && (strtolower($user->role ?? '') === 'resident' || strtolower($user->user_type ?? '') === 'resident');
            AuditLog::create([
                'user_id' => $user->id,
                'action' => $isResident ? 'Log In' : ($isAdmin ? 'Login' : 'Login'),
                'description' => $isResident ? 'Resident logged in' : ($isAdmin ? 'Admin/Super Admin logged in' : 'User logged in'),
            ]);

            
            if ($isAdmin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Create audit log before logging out
        if ($user) {
            $isAdmin = in_array(strtolower($user->role ?? ''), ['admin', 'super admin']) || in_array(strtolower($user->user_type ?? ''), ['admin', 'super admin']);
            $isResident = strtolower($user->role ?? '') === 'resident' || strtolower($user->user_type ?? '') === 'resident';
            AuditLog::create([
                'user_id' => $user->id,
                'action' => $isResident ? 'Log Out' : ($isAdmin ? 'Logout' : 'Logout'),
                'description' => $isResident ? 'Resident logged out' : ($isAdmin ? 'Admin/Super Admin logged out' : 'User logged out'),
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}