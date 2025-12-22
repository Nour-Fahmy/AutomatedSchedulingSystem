<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production or when APP_FORCE_HTTPS is true
        // Check both secure() and X-Forwarded-Proto header (for load balancers)
        $isSecure = $request->secure() || 
                    $request->header('X-Forwarded-Proto') === 'https' ||
                    $request->server('HTTP_X_FORWARDED_PROTO') === 'https';
        
        $forceHttps = app()->environment('production') || env('APP_FORCE_HTTPS', false);
        
        if ($forceHttps && !$isSecure) {
            return redirect()->secure($request->getRequestUri());
        }

        // Set secure cookie flag in response
        $response = $next($request);
        
        // Ensure secure cookies in production or when forced
        if ($forceHttps) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}

