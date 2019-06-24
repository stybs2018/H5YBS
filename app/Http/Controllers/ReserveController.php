<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    //
    public function create(Request $request)
    {
        $params = $request->input();
        $user   = session('user')->fid;
        $count = DB::table('customer_reserve')->whereDate('created_at', date('Y-m-d'))->count();
        $params['rid'] = date('Ymd').sprintf("%05d", ++$count);
        $params['fid'] = $user;
        $params['username'] = $params['name'];
        $params['rtime'] = $params['time'];
        unset($params['time']);
        unset($params['name']);
        $params['created_at'] = date('Y-m-d H:i:s');

        if (is_null(session('user')->realname)) {
            DB::table('customer')->where('fid', $user)->update([
                    'realname' => $params['username'],
                    'telephone' => $params['telephone']
                ]);
            session(['user' => DB::table('customer')->where('fid', $user)->first()]);
        }

        if (DB::table('customer_reserve')->where(['status' => 1, 'fid' => $user])->count()) {
            return ['code' => 3005, 'message' => '您有尚未处理预约'];
        }
        try {
            if (DB::table('customer_reserve')->insert($params)) {
                return ['code' => 3003, 'message' => '预约成功, 稍后将会有客服电话联系您确认'];
            } else {
                return ['code' => 3004, 'message' => '预约失败, 请拨打电话88896666预约'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3004, 'message' => $e->getMessage() ];
            return ['code' => 3004, 'message' => '预约失败, 请拨打电话88896666预约'];
        }
    }
    
    public function cancel(Request $request)
    {
        $user = session('user')->fid;
        
        if (DB::table('customer_reserve')->where(['status' => 1, 'fid' => $user])->update(['status' => 3])) {
            return ['code' => 3006, 'message' => '预约取消成功'];
        } else {
            return ['code' => 3007, 'message' => '预约取消失败'];
        }
    }
}
