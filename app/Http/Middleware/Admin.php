<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

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
        $user = User::getJWTUser();

        if ($user->admin) {
            return $next($request);
        }
        else {
            abort(403, 'Access denied');
        }
    }
}
