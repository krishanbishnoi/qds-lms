<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthFront
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::guest()){
            return Redirect::to('/');
        }
        // if( Auth::user()->user_role_id  != TRAINEE_ROLE_ID){   //old only for trainee Login
            if( Auth::user()->user_role_id  != TRAINEE_ROLE_ID  && Auth::user()->user_role_id  != MANAGER_ROLE_ID && Auth::user()->user_role_id  != TRAINER_ROLE_ID){

			return Redirect::to('/');
		}

        return $next($request);
    }
}
