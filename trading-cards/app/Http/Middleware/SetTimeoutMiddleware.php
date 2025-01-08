<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set timeout to 5 minutes
        set_time_limit(300);
        
        // Increase memory limit
        ini_set('memory_limit', '512M');
        
        return $next($request);
    }
}
