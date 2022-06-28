<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class IsAcct
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
        if (auth()->user()->usertype_id === '2') {
            return $next($request);
        }
        return redirect()->back();
    }
}
