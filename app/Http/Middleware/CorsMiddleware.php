<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        $allowedOrigins = ['http://localhost', 'https://holeify.com', 'https://www.holeify.com', 'https://app.flutterwave.com', 'https://flutterwave.com'];
            // $allowedOrigins = ['*'];

        if (in_array($request->header('Origin'), $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $request->header('Origin'))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $next($request);
    }

}
