<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Crq;

class TestController extends Controller
{
    //
    public function run()
    {
        $appid = env('APPID');
        $appsecret = env('APPSECRET');
        
        $accesstoken = $this->getAccessToken($appid, $appsecret);
        return $accesstoken;
        // $params = ['button' => [
        //     [
        //         'name' => '关于我们',
        //         'sub_button' => [
        //             [
        //                 "type" => "view",
        //                 "name" => "品牌介绍",
        //                 "url" =>  "https://mp.weixin.qq.com/mp/homepage?__biz=MzU5NjU2ODQ0Mg==&hid=4&sn=c70a32516c8da4e8b46986ae6287ed3e&scene=18"
        //             ],
        //             [
        //                 "type" =>"view",
        //                 "name" => "权威专家",
        //                 "url" => "https://mp.weixin.qq.com/s/Oln6onAumZ8T0i737dP8tg"
        //             ],
        //             [
        //                 "type" =>"view",
        //                 "name" => "真人案例",
        //                 "url" => "https://mp.weixin.qq.com/mp/homepage?__biz=MzUyMDk0MDMwNw==&hid=2&sn=00dcb081849c5b0c0d27b963f2ffa196&scene=18"
        //             ],
        //             [
        //                 "type" => "view",
        //                 "name" => "路线导航",
        //                 "url" => "https://mp.weixin.qq.com/s/TD4ikF73ypLpRdJEXBM2Aw"
        //             ]
        //         ]
        //     ]
        // ]];
        
        // var_dump($this->curl_post("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accesstoken", $params));
    }
    
    public function getAccessToken($appid, $appsecret)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $res = Crq::get($url);
        $res = json_decode($res,1);
        if(isset($res['errcode']) && $res['errcode']!=0) {
            throw new \Exception($res['errmsg']);
        }
        return $res['access_token'];
    }

}
