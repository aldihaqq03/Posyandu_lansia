<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $otp = (string) random_int(100000, 999999);
        $cacheKey = $this->otpCacheKey($request->email);

        Cache::put($cacheKey, $otp, now()->addMinutes(10));

        Mail::raw("Kode OTP reset password SIMPEL Anda adalah: {$otp}\n\nKode berlaku selama 10 menit. Jika Anda tidak meminta reset password, abaikan email ini.", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Kode OTP Reset Password SIMPEL');
        });

        return redirect()
            ->route('password.otp.form', ['email' => $request->email])
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    public static function otpCacheKey(string $email): string
    {
        return 'password-reset-otp:' . strtolower($email);
    }
}