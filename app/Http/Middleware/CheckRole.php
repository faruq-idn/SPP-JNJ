<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            Log::warning('Unauthorized access attempt - Not logged in', [
                'path' => $request->path(),
                'ip' => $request->ip()
            ]);
            return redirect('login');
        }

        $user = Auth::user();
        
        Log::info('CheckRole middleware', [
            'user_id' => $user->id,
            'required_role' => $role,
            'user_role' => $user->role,
            'path' => $request->path()
        ]);

        if ($user->role !== $role) {
            Log::warning('Unauthorized access attempt - Wrong role', [
                'user_id' => $user->id,
                'required_role' => $role,
                'user_role' => $user->role,
                'path' => $request->path()
            ]);
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
