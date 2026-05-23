<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function otpForm(Request $request): View
    {
        return view('auth.verify-otp', [
            'email' => $request->query('email'),
        ]);
    }

    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $cacheKey = PasswordResetLinkController::otpCacheKey($request->email);
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp || $cachedOtp !== $request->otp) {
            return back()->withInput($request->only('email'))->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $request->session()->put('password_reset_verified_email', $request->email);

        return redirect()->route('password.reset', [
            'token' => Str::random(40),
            'email' => $request->email,
        ]);
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:6'],
    ]);

    $verifiedEmail = $request->session()->get('password_reset_verified_email'); // get, bukan pull

    if ($verifiedEmail !== $request->email) {
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Sesi verifikasi OTP tidak valid. Silakan mulai ulang.']);
    }

    $user = User::where('email', $request->email)->first();

    if (! $user) {
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    $user->forceFill([
        'password' => Hash::make($request->password),
        // remember_token dihapus
    ])->save();

    event(new PasswordReset($user));

    Cache::forget(PasswordResetLinkController::otpCacheKey($request->email));
    $request->session()->forget('password_reset_verified_email');

    return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan masuk.');
}
}