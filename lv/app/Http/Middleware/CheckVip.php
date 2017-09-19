<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;


class CheckVip
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
        if (null == $user || $user->vip == 0) {
            //不是vip不能写信 提示开通vip
            return Redirect::route('no_vip');
        }
        return $next($request);
    }
}
