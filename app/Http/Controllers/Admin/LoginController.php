<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Captcha;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\LoginForm;
use App\Models\Admin\Auth; 

class LoginController extends Controller
{
    //  验证码
    public function captcha(Request $request)
    {
        $vercode = new Captcha(2, 100, 30);
        Cache::put($request->query('_token'), $vercode->getRes()['code'], 120);
    }
    
    //  校验账密
    public function _login(Request $request)
    {
        if (Cache::pull($request->query('_token')) !== $request->input('vercode')) {
            return ['code' => 2002, 'message' => '验证码错误'];
        }
        
        $model = new LoginForm($request->input('username', null), $request->input('password', null));
        
        if (!$model->validate()) {
            return ['code' => 2001, 'message' => '用户名或密码错误'];
        }
        
        $token = md5(time().round(10, 99));
        Cache::forever($token, $model->getUser());
        
        return ['code' => 2000, 'message' => '登录成功', 'access_token' => $token];
    }
    
    //  登录
    public function login(Request $request)
    {
        $admin = Cache::pull($request->input('access_token'));
        unset($admin->password);
        session(['admin' => $admin]);
        session(['permission' => LoginForm::getAuth($admin->role)]);
        return redirect(env('ADMIN_PREFIX', '_admin'));
    }
    
    // 登出
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect(env('ADMIN_PREFIX', '_admin'));
    }
}
