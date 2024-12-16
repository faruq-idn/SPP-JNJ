<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('PreventBackHistory middleware running');

        $response = $next($request);

        Log::info('Response type: ' . get_class($response));

        if (!method_exists($response, 'header')) {
            Log::warning('Response does not have header method');
            return $response;
        }

        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
