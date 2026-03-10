<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\User;

class TwoFactorController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.2fa');
    }

    public function sendCode(Request $request)
    {
        $user = Auth::user();
        $code = random_int(100000, 999999);
        Session::put('2fa_code', $code);
        Session::put('2fa_expires', now()->addMinutes(5));

        // Send code via SMS using Twilio
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_FROM');
        $twilio = new \Twilio\Rest\Client($sid, $token);

        try {
            $twilio->messages->create(
                $user->contact_number, // User's phone number
                [
                    'from' => $from,
                    'body' => 'Your 2FA code is: ' . $code
                ]
            );
            Log::info('2FA SMS sent to user ' . $user->id);
            return back()->with('status', '2FA code sent via SMS!');
        } catch (\Exception $e) {
            Log::error('Twilio SMS failed: ' . $e->getMessage());
            return back()->withErrors(['code' => 'Failed to send SMS. Please try again.']);
        }
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);
        $code = Session::get('2fa_code');
        $expires = Session::get('2fa_expires');

        if (!$code || !$expires || now()->gt($expires)) {
            return back()->withErrors(['code' => 'Code expired. Please request a new one.']);
        }

        if ($request->code == $code) {
            Session::forget('2fa_code');
            Session::forget('2fa_expires');
            Session::put('2fa_passed', true);
            return redirect()->intended('/home');
        }

        return back()->withErrors(['code' => 'Invalid code.']);
    }
}
