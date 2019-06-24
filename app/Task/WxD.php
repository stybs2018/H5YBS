<?php

namespace App\Task;

use App\Lib\Crq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WxD
{
    public function getAccessToken($appid, $appsecret)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $res = Crq::get($url);
        if(isset($res['errcode']) && $res['errcode']!=0) {
            throw new \Exception($res['errmsg']);
        }
        return $res['access_token'];
    }
    
    public function run()
    {
        $appid = env('APPID');
        $appsecret = env('APPSECRET');
        $accesstoken = $this->getAccessToken($appid, $appsecret);
        
        try {
            $date = date('Y-m-d');
            $start =  date("Y-m-d",strtotime("-1 day", strtotime($date)));
            $end = $start;
            $params = [
                'begin_date' => $start,
                'end_date' => $end
            ];
            
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
            
            DB::table('wxs_ad')->insert($insert);
            Log::channel('tasklog')->warning('每日获取微信公众号粉丝任务执行成功!');
        } catch (\Exception $e) {
            Log::channel('tasklog')->warning('每日获取微信公众号粉丝任务执行失败!');
        }
    }
}