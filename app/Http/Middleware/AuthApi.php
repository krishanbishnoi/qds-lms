<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
Use Redirect;

class AuthApi
{
    /**
    * Run the request filter.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next){
		// if(Auth::guard('api')->guest()){
		//     $response     =   array('error'=>true,'data'=>['message'=>'Please login your account first!']);
		//     echo json_encode($response);die;
		// }
        return $next($request);
    }
}
