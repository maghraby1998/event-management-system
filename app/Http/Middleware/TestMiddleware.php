<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $connectionType = $request->headers->get("connection");

        if ($connectionType == "keep-alive") {
            throw new \Exception("test throwing an exception from middelware");
        }

        return $next($request);
    }
}
