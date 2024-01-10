<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AfterHTMLInjector
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        echo "</b>nachher<br>";

        return $response;
    }
}
