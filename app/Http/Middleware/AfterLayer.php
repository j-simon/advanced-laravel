<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AfterLayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response =  $next($request);

        //echo "after layer";

        $bisherigerBladeInhalt = $response->getContent();

        //dd($bisherigerBladeInhalt);

        // inhalt aus der before middleware empfangen
        $vorherInhaltausderBefoeMiddleware = $request->attributes->get('beforeInhalt');

        $neuerInhalt = str_replace("core", ($vorherInhaltausderBefoeMiddleware . "<br>core"), $bisherigerBladeInhalt);
        
        $neuerInhalt = str_replace("core", "core<br>afterLayer", $neuerInhalt);
        
        $response->setContent($neuerInhalt);
        
        

        return $response;;
    }
}
