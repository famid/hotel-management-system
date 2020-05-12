<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role == ADMIN_ROLE && Auth::user()->status == USER_ACTIVE_STATUS) {
            return $next($request);
        }

        Auth::logout();
        return redirect()->route('signIn')->with(['error' => __('You are not authorized')]);
    }
}
