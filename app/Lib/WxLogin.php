<?php
namespace App\Lib;

/**
 * 微信授权登录
 */

class WxLogin 
{
    private $appid = '';
    private $appsecret = '';
    private $callback = '';
    private $local = '';
    
    function __construct($callback)
    {
        $this->appid = 'wx1de37bab8e684be3';
        $this->appsecret = '37ac45f5679619041078b8e0a458c24b';
        $this->callback = $callback;
        $this->local = 'https://h5.styay.com';
        
        if (!($this->appid && $this->appsecret)) {
            echo json_encode(['code' => -1, 'message' => '微信授权登录配置错误']);
            exit;
        }
    }
    
    //  获取用户
    public function getUser($code)
    {
        if (is_null($code)) {
            // 无Code
            $code = $this->getCode();
            return $this->getUser($code);
        } else {
            // 有Code
            $data = $this->getAccessToken($code);
            $userdata = $this->getUserinfo($data);
            return $userdata;
        }
    }
    
    //  获取Code
    private function getCode()
    {
        $callback = urlencode($this->local.'?rwurl='.$this->callback);
        $scope = 'snsapi_userinfo';
	    $state = md5(uniqid(rand(), TRUE));
	    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appid . '&redirect_uri=' . $callback .  '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
        header("Location:$url");
    }
    
    //  获取AccessToken
    private function getAccessToken($code)
    {
        $appid = $this->appid;
        $appsecret = $this->appsecret;	
    	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
    	$user = json_decode(file_get_contents($url));
    	if (isset($user->errcode)) {
            echo json_encode(['code' => $response->errcode, 'message' => $response->errmsg]);
            exit;
    	}
    	$data = json_decode(json_encode($user),true);
    	return $data;
    }
    
    // 获取用户数据
    private function getUserinfo($data) 
    {
        $openid = $data['openid'];
        $access_token = $data['access_token'];
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $response = json_decode(file_get_contents($url));
        if (isset($response->errcode)) {
            echo json_encode(['code' => $response->errcode, 'message' => $response->errmsg]);
    	    exit;
    	}
    	$data = json_decode(json_encode($response),true);//返回的json数组转换成array数组
    	return $data;
    }
}