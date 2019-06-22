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
        $prefix = env('ADMIN_PREFIX', 'admin');
        
        // 登录检查
        if (!$request->session()->has('admin')) {
            return redirect($prefix . '/login');
        }
        
        $permission = $request->session()->get('permission');
        
        $excpet = [];
        
        $path = explode($prefix, $request->path())[1];
        
        if (!in_array($path, $excpet) && !in_array($path, $permission['Page'])) {
            return redirect($prefix . '/login');
        }
        
        return $next($request);
    }
}
