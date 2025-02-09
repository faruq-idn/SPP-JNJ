<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SantriRouteDebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('Debug Route Information', [
            'url' => $request->url(),
            'method' => $request->method(),
            'route' => $request->route() ? [
                'name' => $request->route()->getName(),
                'parameters' => $request->route()->parameters(),
                'action' => $request->route()->getActionName(),
                'is_admin_santri_edit' => $request->route()->getName() === 'admin.santri.edit'
            ] : null,
            'path_info' => $request->getPathInfo(),
            'segments' => $request->segments()
        ]);

        return $next($request);
    }
}
