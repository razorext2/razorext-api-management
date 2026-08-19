<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove sensitive headers
        header_remove('X-Powered-By');
        header_remove('Server');

        if (method_exists($response, 'header')) {
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Referrer-Policy', 'no-referrer-when-downgrade');
            $response->header('Content-Security-Policy', 'upgrade-insecure-requests');
            $response->header('Permissions-Policy', 'unload=*');
        }

        return $response;
    }
}
