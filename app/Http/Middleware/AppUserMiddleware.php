<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AppUserMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (empty($user) || $user->role != USER_ROLE) {
            throw new AuthenticationException('You are not authorized');
        }

        return $next($request);
    }
}
