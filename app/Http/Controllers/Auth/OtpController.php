<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect('/login')->withErrors(['otp' => 'Session habis, silakan login ulang.']);
        }

        if ($request->otp != $user->otp) {
            return back()->withErrors(['otp' => 'OTP salah.']);
        }

        // OTP valid → login user
        $user->update(['otp' => null]);
        Auth::login($user);
        $request->session()->forget('otp_user_id');
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function resend()
    {
        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect('/login')->withErrors(['otp' => 'Session habis, silakan login ulang.']);
        }

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);

        Mail::raw("Kode OTP kamu: $otp", function ($m) use ($user) {
            $m->to($user->email)->subject('Kode OTP Login');
        });

        return back()->with('success', 'OTP baru sudah dikirim ke email kamu.');
    }
}
