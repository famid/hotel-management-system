<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
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
        if (Auth::user()->role == SUPER_ADMIN_ROLE && Auth::user()->status == USER_ACTIVE_STATUS) {
            return $next($request);
        }

        Auth::logout();
        return redirect()->route('signIn')->with(['error' => __('You are not authorized')]);
    }
}
