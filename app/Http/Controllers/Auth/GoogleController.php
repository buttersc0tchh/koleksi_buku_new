<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

   public function callback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Cari user by email dulu
    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        if (!$user->id_google) {
            $user->update([
                'id_google' => $googleUser->getId(),
            ]);
        }
    } else {
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'id_google' => $googleUser->getId(),
            'password' => bcrypt(Str::random(16)),
        ]);
    }

    // Generate OTP
$otp = rand(100000, 999999);
$user->update(['otp' => $otp]);

// Simpan user id di session
session(['otp_user_id' => $user->id]);

// Kirim OTP ke email
Mail::raw("Kode OTP kamu: $otp", function ($m) use ($user) {
    $m->to($user->email)->subject('Kode OTP Login');
});

return redirect()->route('verify.otp');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
