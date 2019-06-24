<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;
use App\Lib\WxLogin as WxL;
use Illuminate\Support\Facades\Cache;


class WxLogin
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
        $open = true;
        
        $model = new Customer();
        
        if ($open) {
            if (!$request->session()->has('user')) {
                $wx = new WxL($request->path());
                $res = $wx->getUser($request->query('code', null));
                $customer = $model->getByOpenid($res['openid']);
                // 不是会员则注册
                if (!$customer) {
                    $customer = $model->register($res);
                }
                if (!$customer) {
                    echo json_encode('注册失败, 请联系管理员!');
                    exit;
                }                
                session(['user' => $customer]);
                $token = md5($customer->id.time());
                setcookie('user-token', $token, time()+ 18400);
                Cache::forever($token, $customer);
            } else {
                $model->loginnow(session('user')->id);
            }
        }
        
        return $next($request);
    }
}
