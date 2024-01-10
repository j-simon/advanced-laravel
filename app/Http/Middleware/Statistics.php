<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Statistics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //echo "ich werden vor deinem eigenen code eine statistik führen";
        // mitzählen für das Marketing
        //$zaeeler=1;
        // oder loggen!
        Log::channel('statistics')->info("Die Impressum Seite wurde aufgerufen");
        Log::channel('slack')->info("Die Impressum Seite wurde aufgerufen");
        logger()->channel('statistics')->critical('Hilfe');
        //return redirect()->away("https://google.de");
       return $next($request); // reicht dei Verarbeitung weiter in deine callback oder controller-method
    }
}
