<?php

namespace App\Http\Middleware;

use Closure;

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
            return '0';
        }
        return $next($request);
    }
}
