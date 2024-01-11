<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeLayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       // echo "before layer";

        
        // Ein Attribut setzen, das in der after middleware wieder ausgelesen werden kann
        $beforeInhalt = 'before layer'; 
        $request->attributes->add(['beforeInhalt' => $beforeInhalt]);
      
        return $next($request);
    }
}
