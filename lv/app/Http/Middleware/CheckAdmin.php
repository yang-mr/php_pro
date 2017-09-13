<?php

namespace App\Http\Middleware;
use Auth;
use Illuminate\Http\Request;
use Redirect;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (null == $user || !$user->is_admin) {
                return Redirect::route('admin_login');
            }
        return $next($request);
    }
}
