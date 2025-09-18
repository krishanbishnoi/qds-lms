<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class CheckAdminSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the session ID matches
            if ($user->session_id !== session()->getId()) {
                Auth::logout(); // Log out if the session ID doesn't match
                // Session::flash('flash_notice', 'Your session has expired due to a new login in another system or browser. Please log in again to continue.');
                return redirect('admin/login')->with('error', 'Your session has expired due to a new login in another system or browser. Please log in again to continue.');
            }
        }

        return $next($request);
    }
}
