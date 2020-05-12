<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class VerifiedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (empty($user) || $user->status != USER_ACTIVE_STATUS) {
            throw new AuthenticationException('You are not authorized');
        }

        return $next($request);
    }
}
