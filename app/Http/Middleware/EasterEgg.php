<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \Carbon\Carbon;

class EasterEgg
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->easteregg) { // <form method="POST">
            
            $easter = Carbon::createFromDate(null, 1, 11); // 12.4.xxxx
            if ($easter->isToday()) {
                return redirect()->away('https://images.unsplash.com/photo-1522184462610-d9493b82cdf2?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=564&amp;q=80');
            } else {
                abort(403, 'Its not easter yet');
            }
        
        }

        return $next($request);
    }
}
