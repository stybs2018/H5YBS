<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Crq;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    //
    // public function run()
    // {
    //     $appid = env('APPID');
    //     $appsecret = env('APPSECRET');
        
    //     $accesstoken = $this->getAccessToken($appid, $appsecret);
    //     return $accesstoken;
    //     // $params = ['button' => [
    //     //     [
    //     //         'name' => '关于我们',
    //     //         'sub_button' => [
    //     //             [
    //     //                 "type" => "view",
    //     //                 "name" => "品牌介绍",
    //     //                 "url" =>  "https://mp.weixin.qq.com/mp/homepage?__biz=MzU5NjU2ODQ0Mg==&hid=4&sn=c70a32516c8da4e8b46986ae6287ed3e&scene=18"
    //     //             ],
    //     //             [
    //     //                 "type" =>"view",
    //     //                 "name" => "权威专家",
    //     //                 "url" => "https://mp.weixin.qq.com/s/Oln6onAumZ8T0i737dP8tg"
    //     //             ],
    //     //             [
    //     //                 "type" =>"view",
    //     //                 "name" => "真人案例",
    //     //                 "url" => "https://mp.weixin.qq.com/mp/homepage?__biz=MzUyMDk0MDMwNw==&hid=2&sn=00dcb081849c5b0c0d27b963f2ffa196&scene=18"
    //     //             ],
    //     //             [
    //     //                 "type" => "view",
    //     //                 "name" => "路线导航",
    //     //                 "url" => "https://mp.weixin.qq.com/s/TD4ikF73ypLpRdJEXBM2Aw"
    //     //             ]
    //     //         ]
    //     //     ]
    //     // ]];
        
    //     // var_dump($this->curl_post("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accesstoken", $params));
    // }
    
    public function getAccessToken($appid, $appsecret)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $res = Crq::get($url);
        if(isset($res['errcode']) && $res['errcode']!=0) {
            throw new \Exception($res['errmsg']);
        }
        return $res['access_token'];
    }

    // 获取公众号数据
    public function run()
    {
        $appid = env('APPID');
        $appsecret = env('APPSECRET');
            $accesstoken = $this->getAccessToken($appid, $appsecret);
        
        $date = date('Y-m-d');
        $start = '2018-06-01';
        $end = date("Y-m-d",strtotime("+6 day", strtotime($start)));
        $params = [
            'begin_date' => $start,
            'end_date' => $end
        ];
        
        try {
            while(strtotime($start) < strtotime($date) ) {
                if (strtotime($end) > strtotime($date)) {
                    $end = date("Y-m-d",strtotime("-1 day", strtotime($date)));
                }
                $params['begin_date'] = $start;
                $params['end_date'] = $end;
                $a = Crq::post("https://api.weixin.qq.com/datacube/getusersummary?access_token=$accesstoken", $params)['list'];
                $b = Crq::post("https://api.weixin.qq.com/datacube/getusercumulate?access_token=$accesstoken", $params)['list'];
                $data = [];
                $insert = [];
                foreach ($b as $i) {
                    $data[$i['ref_date']]['sum'] = $i['cumulate_user'];
                    $user_source = [];
                    $addition = 0;
                    $subtraction = 0;
                    foreach ($a as $j) {
                        if ($j['ref_date'] === $i['ref_date']) {
                            $addition += $j['new_user'];
                            $subtraction -= $j['cancel_user'];
                            $user_source[$j['user_source']] = $j['new_user'];
                        }
                    }
                    $diff = $addition + $subtraction;
                    $data[$i['ref_date']]['addition'] = $addition;
                    $data[$i['ref_date']]['subtraction'] = abs($subtraction);
                    $data[$i['ref_date']]['diff'] = $diff;
                    $data[$i['ref_date']]['user_source'] = serialize($user_source);
                }
                
                foreach ($data as $k => $v) {
                    array_push($insert, array_merge(['created_at' => $k], $v));
                }
                $start = date("Y-m-d",strtotime("+7 day", strtotime($start)));
                $end = date("Y-m-d",strtotime("+7 day", strtotime($end)));
                
                DB::table('wxs_ad')->insert($insert);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } finally {
            return $params;
        }
    }
}
