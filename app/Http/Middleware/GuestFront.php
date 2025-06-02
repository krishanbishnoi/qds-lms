<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class GuestFront 
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(!empty(Auth::user()) && (Auth::user()->user_role_id  == TRAINEE_ROLE_ID)){
          return Redirect::to('/dashboard');
        }
        if(!empty(Auth::user()) && (Auth::user()->user_role_id  == SUPER_ADMIN_ROLE_ID)){
          return Redirect::to('/admin/dashboard');
        }
        if(!empty(Auth::user()) && (Auth::user()->user_role_id  == TRAINER_ROLE_ID)){
          return Redirect::to('/trainer/dashboard');
        }

        
		    return $next($request);
    }
}
