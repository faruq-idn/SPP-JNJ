<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Throttle login attempts
        if (! RateLimiter::tooManyAttempts($request->email, 5)) {
            if (Auth::attempt($request->only('email', 'password'))) {
                RateLimiter::clear($request->email);
                $request->session()->regenerate();

                $user = Auth::user();
                Log::info('User Login', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);

                // Redirect langsung ke dashboard sesuai role tanpa memperhatikan intended URL
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'petugas') {
                    return redirect()->route('petugas.dashboard');
                } elseif ($user->role === 'wali') {
                    return redirect()->route('wali.dashboard');
                }
            } else {
                RateLimiter::hit($request->email);

                throw ValidationException::withMessages([
                    'email' => 'Email atau password salah.',
                ]);
            }
        } else {
            return back()->withErrors([
                'email' => __('auth.throttle', [
                    'seconds' => RateLimiter::availableIn($request->email),
                ]),
            ])->onlyInput('email');
        }
    }

    /**
     * Get the rate limiting throttle key for the login requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(strtolower($request->input('email').'|'.$request->ip()));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Anda berhasil logout')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
