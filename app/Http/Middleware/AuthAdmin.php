<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;
Use Config;
class AuthAdmin 
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
			return Redirect::to('admin/login');
        }

        // $SUPER_ADMIN_ROLE_ID  =   Config::get('constants.user.SUPER_ADMIN_ROLE_ID');
		   
        if( Auth::user()->user_role_id  != SUPER_ADMIN_ROLE_ID && Auth::user()->user_role_id  != MANAGER_ROLE_ID){
			return Redirect::to('/');
		}
        return $next($request);
    }
}
