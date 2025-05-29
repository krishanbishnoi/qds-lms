<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthVendor 
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
			return Redirect::to('/vendorpanel/login');
		}
		if(Auth::user()->user_role_id  != VENDOR_ROLE_ID){
			return Redirect::to('/vendorpanel');
		}
        return $next($request);
    }
}
