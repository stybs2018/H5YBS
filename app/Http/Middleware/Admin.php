<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        // 登录检查
        if (!$request->session()->has('admin')) {
            return redirect(env('ADMIN_PREFIX', 'admin').'/login');
        }
        
        return $next($request);
    }
}
