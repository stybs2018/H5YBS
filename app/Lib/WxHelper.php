<?php

namespace App\Lib;

use App\Lib\Crq;

class WxHelper
{
    private $appid;
    private $appsecret;

    function __construct()
    {
        $this->appid = env('APPID');
        $this->appsecret = env('APPSECRET');
    }
    
    
    
    // 发送模板消息
    public function sendMsg($openid, $tpl, $message)
    {
        $params = [];
        $params['touser'] = $openid;
        $params['template_id'] = $tpl;
        $params['data'] = $message;
        $accesstoken = $this->getAccessToken();
        $res = Crq::post("https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accesstoken", $params);
        return json_decode($res)->errcode === 0;
    }
    
    // 获取AccessToken
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $res = Crq::get($url);
        $res = json_decode($res,1);
        if(isset($res['errcode']) && $res['errcode']!=0) {
            throw new \Exception($res['errmsg']);
        }
        return $res['access_token'];
    }

}