<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Customer 
{
    // 注册
    public function register($data)
    {
        try {
            $fid = date('Ym').'000'.time();
            if ($id = DB::table('customer')->insertGetId([
                    'fid'   => substr($fid, 2, strlen($fid)),
                    'openid' => $data['openid'],
                    'nickname' => $data['nickname'],
                    'sex'   => $data['sex'],
                    'city'  => $data['city'],
                    'province'  => $data['province'],
                    'country'   => $data['country'],
                    'avatar'    => $data['headimgurl'],
                    'created_at'=> date('Y-m-d H:i:s'),
                    'logined_at'=> date('Y-m-d H:i:s')
            ])) {
                return DB::table('customer')->where('id', $id)->first();
            } else {
                return false;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }
    
    // 获取顾客数据
    public function getByOpenid($openid)
    {
        return DB::table('customer')
            ->where('openid', $openid)
            ->first();
    }
    
    //  更新最后访问时间
    public function loginnow($id) {
        DB::table('customer')->where('id', $id)->update(['logined_at' => date('Y-m-d H:i:s')]);
    }
    
    //  登录
    public function login()
    {
        
    }
}