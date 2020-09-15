<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class UserMiddleware
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
        if(!Auth::guard('user')->check()){
            return redirect('/')->with('err','No loggin session has been saved, Please login again.');
        }
        return $next($request);
    }
}
