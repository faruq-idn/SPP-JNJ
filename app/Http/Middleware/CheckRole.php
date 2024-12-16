<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        Log::info('Role Check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_role' => $role
        ]);

        if ($user->role === $role) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
