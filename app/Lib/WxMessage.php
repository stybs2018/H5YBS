<?php

namespace App\Lib;

class WxMessage 
{
    private $openid;
    private $message;
    private $appid;
    private $appsecret;
    private $tpl;
    
    function __construct($openid, $tpl, $message)
    {
        $this->tpl = $tpl;
        $this->appid = 'wx1de37bab8e684be3';
        $this->appsecret = '37ac45f5679619041078b8e0a458c24b';
        $this->openid = $openid;
        $this->message = $message;
    }
    
    public function send()
    {
        $params = [];
        $params['touser'] = $this->openid;
        $params['template_id'] = $this->tpl;
        $params['data'] = $this->message;
        $accesstoken = $this->getAccessToken();
        return $this->curl_post("https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accesstoken", $params);
    }
    
    public function getAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $res = $this->curl_get($url);
        $res = json_decode($res,1);
        
        if(isset($res['errcode']) && $res['errcode']!=0) throw new Exception($res['errmsg']);
        return $res['access_token'];
    }
    
    public function curl_get($url) {
        $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36');
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 20);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    
    public function curl_post($url, $data) {
        $curl = curl_init();
      //设置抓取的url
      curl_setopt($curl, CURLOPT_URL, $url);
      //设置头文件的信息作为数据流输出
      //设置获取的信息以文件流的形式返回，而不是直接输出。
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      //设置post方式提交
      curl_setopt($curl, CURLOPT_HEADER, FALSE);    //表示需要response header
        curl_setopt($curl, CURLOPT_NOBODY, TRUE);
     curl_setopt($curl, CURLOPT_POST, 1);
     curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
     //设置post数据
     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
     //执行命令
        $response = curl_exec($curl);
     //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $response;
    }
}