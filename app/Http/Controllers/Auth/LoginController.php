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

        // Log login attempt
        Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        // Throttle login attempts
        if (! RateLimiter::tooManyAttempts($request->email, 5)) {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                
                // Log successful login
                Log::info('User logged in successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role
                ]);

                RateLimiter::clear($request->email);
                $request->session()->regenerate();

                // Direct URL redirection based on role
                return match($user->role) {
                    'admin' => redirect('/admin/dashboard'),
                    'petugas' => redirect('/petugas/dashboard'),
                    'wali' => redirect('/wali/dashboard'),
                    default => redirect('/')
                };
            } else {
                RateLimiter::hit($request->email);

                Log::warning('Login failed - Invalid credentials', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);

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

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Log::info('User logging out', [
            'user_id' => $user ? $user->id : 'none',
            'role' => $user ? $user->role : 'none'
        ]);

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
