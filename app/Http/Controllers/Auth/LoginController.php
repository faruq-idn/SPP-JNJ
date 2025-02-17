<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
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
            if (RateLimiter::tooManyAttempts($request->email, 5)) {
                $seconds = RateLimiter::availableIn($request->email);
                return response()->json([
                    'message' => __('auth.throttle', ['seconds' => $seconds])
                ], 429);
            }

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

                // Return redirect URL based on role
                $redirectUrl = match($user->role) {
                    'admin' => '/admin/dashboard',
                    'petugas' => '/petugas/dashboard',
                    'wali' => '/wali/dashboard',
                    default => '/'
                };

                return response()->json(['url' => $redirectUrl]);
            }

            RateLimiter::hit($request->email);

            Log::warning('Login failed - Invalid credentials', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => 'Email atau password salah.'
            ], 422);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat login.'
            ], 500);
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
