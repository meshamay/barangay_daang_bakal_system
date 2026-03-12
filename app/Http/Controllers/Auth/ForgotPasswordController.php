<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class ForgotPasswordController extends Controller {
    public function resendOtp(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return back()->withErrors(['email' => 'Email is required to resend OTP.']);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }
        // Generate new OTP
        $otp = random_int(100000, 999999);
        $table = config('auth.passwords.users.table', 'password_reset_tokens');
        DB::table($table)->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );
        // Send OTP via email
        \Mail::raw('Your OTP for password reset is: ' . $otp, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });
        return redirect()->route('password.verify-otp', ['email' => $email])
            ->with('status', 'A new OTP has been sent to your email.');
    }

    public function verifyOtpSubmit(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'array', 'size:6'],
            'otp.*' => ['required', 'digits:1'],
            'email' => ['required', 'email'],
        ]);

        $otpInput = implode('', $request->input('otp'));
        $email = $request->input('email');
        $table = config('auth.passwords.users.table', 'password_reset_tokens');
        $record = \DB::table($table)->where('email', $email)->first();

        if (!$record) {
            return back()->withErrors(['email' => 'No OTP found for this email.'])->withInput();
        }

        // Check OTP validity
        if (!\Hash::check($otpInput, $record->token)) {
            return back()->withErrors(['otp' => 'Invalid OTP code.'])->withInput();
        }

        // Optionally: check expiration (e.g., 10 min)
        $created = \Carbon\Carbon::parse($record->created_at);
        if ($created->diffInMinutes(now()) > 10) {
            return back()->withErrors(['otp' => 'OTP has expired.'])->withInput();
        }

        // OTP verified, redirect to reset password form
        return redirect()->route('password.reset', ['token' => $record->token, 'email' => $email]);
    }
    private function normalizePhone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', trim($value)) ?? '';

        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            return substr($digits, 2);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return substr($digits, 1);
        }

        return $digits;
    }

    private function phoneCandidates(string $value): array
    {
        $normalized = $this->normalizePhone($value);
        $candidates = [$normalized, '0' . $normalized, '63' . $normalized, '+63' . $normalized, trim($value)];

        return array_values(array_unique(array_filter($candidates)));
    }

    private function findUserByPhone(string $value): ?User
    {
        return User::whereIn('contact_number', $this->phoneCandidates($value))->first();
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = trim((string) $request->input('email'));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find an account with that email address.'])->withInput();
        }

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        $table = config('auth.passwords.users.table', 'password_reset_tokens');

        DB::table($table)->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // Send OTP via email
        \Mail::raw('Your OTP for password reset is: ' . $otp, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        // Redirect to OTP verification page
        return redirect()->route('password.verify-otp', ['email' => $user->email])
            ->with('status', 'OTP sent to your email. Enter the code to verify.');
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->query('email');
        // You may want to add logic to check if the email exists or if OTP is still valid
        return view('auth.verify-otp', ['email' => $email]);
    }

    // Show OTP verification form
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.verify-otp', ['email' => $email]);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'contact_number' => $request->query('contact_number'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'contact_number' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $contactNumber = trim((string) $request->input('contact_number'));
        $normalizedPhone = $this->normalizePhone($contactNumber);
        $table = config('auth.passwords.users.table', 'password_reset_tokens');
        $tokenRow = DB::table($table)->where('email', $normalizedPhone)->first();

        if (!$tokenRow) {
            return back()->withErrors(['contact_number' => 'Reset request not found for this phone number.'])->withInput();
        }

        $expiresAt = now()->subMinutes((int) config('auth.passwords.users.expire', 60));
        if ($tokenRow->created_at && Carbon::parse($tokenRow->created_at)->lt($expiresAt)) {
            return back()->withErrors(['contact_number' => 'This reset request has expired. Please request a new one.'])->withInput();
        }

        if (!Hash::check($request->input('token'), $tokenRow->token)) {
            return back()->withErrors(['contact_number' => 'Invalid reset token. Please request a new reset.'])->withInput();
        }

        $user = $this->findUserByPhone($contactNumber);
        if (!$user) {
            return back()->withErrors(['contact_number' => 'Account not found for this phone number.'])->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();

        // Log password reset attempt
        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'action' => 'password_reset',
            'description' => 'Password reset via forgot password flow',
            'created_at' => now(),
        ]);

        DB::table($table)->where('email', $normalizedPhone)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now sign in.');
    }
}
