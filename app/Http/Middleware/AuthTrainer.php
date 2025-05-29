<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthTrainer 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		if (Auth::guest()) {
			return Redirect::to('/trainer/login');
		}
		if(Auth::user()->user_role_id  != TRAINER_ROLE_ID){
			return Redirect::to('/trainer');
		}
        return $next($request);
    }
}
