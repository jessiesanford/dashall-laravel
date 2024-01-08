<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class VerifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_group < 3) {
            return redirect('home');
        }

        return $next($request);
    }
}
