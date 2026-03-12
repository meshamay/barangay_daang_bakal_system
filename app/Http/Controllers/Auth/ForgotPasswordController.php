<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ForgotPasswordController extends Controller
{
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
            'contact_number' => ['required', 'string'],
        ]);

        $contactNumber = trim((string) $request->input('contact_number'));
        $user = $this->findUserByPhone($contactNumber);

        if (!$user) {
            return back()->withErrors(['contact_number' => 'We could not find an account with that phone number.'])->withInput();
        }

        $normalizedPhone = $this->normalizePhone((string) $user->contact_number);

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        $table = config('auth.passwords.users.table', 'password_reset_tokens');

        DB::table($table)->updateOrInsert(
            ['email' => $normalizedPhone],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        // Send OTP via SMS (development: log to file)
        $smsService = new \App\Services\FakeSmsService();
        $smsService->send($user->contact_number, 'Your OTP for password reset is: ' . $otp);

        return redirect()->route('password.reset', [
            'token' => $otp,
            'contact_number' => $user->contact_number,
        ])->with('status', 'OTP sent to your phone. Enter the code to reset your password.');
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
