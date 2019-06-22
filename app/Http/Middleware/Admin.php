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
        $prefix = env('ADMIN_PREFIX', '_admin');
        
        // 登录检查
        if (!$request->session()->has('admin')) {
            return redirect($prefix . '/login');
        }
        
        $permission = $request->session()->get('permission');
        
        $excpet = [$prefix];
        
        if (!in_array($request->path(), $excpet) && !in_array($request->path(), $permission['Page'])) {
            abort(403, '无权限访问此页面');
        }
        
        return $next($request);
    }
}
