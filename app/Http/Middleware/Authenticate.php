<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    public function handle($request, Closure $next, ...$guards)
    {
        if($jwt = $request->cookie(key: 'jwt')){
            $request->headers->set(key: 'Authorization', values: 'Bearer '.$jwt);
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }
}
