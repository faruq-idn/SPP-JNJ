<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                Log::info('RedirectIfAuthenticated Middleware', [
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'path' => $request->path(),
                    'guard' => $guard
                ]);

                // Use switch for better readability
                switch ($user->role) {
                    case 'admin':
                        Log::info('Redirecting admin to dashboard');
                        return redirect()->route('admin.dashboard');
                    case 'petugas':
                        Log::info('Redirecting petugas to dashboard');
                        return redirect()->route('petugas.dashboard');
                    case 'wali':
                        Log::info('Redirecting wali to dashboard');
                        return redirect()->route('wali.dashboard');
                    default:
                        Log::warning('Unknown user role', ['role' => $user->role]);
                        Auth::logout();
                        return redirect()->route('login')->with('error', 'Invalid user role');
                }
            }
        }

        return $next($request);
    }
}
