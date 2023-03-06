<?php

namespace Elfcms\Shop\Http\Middleware;

use Closure;

class ApiJson
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
