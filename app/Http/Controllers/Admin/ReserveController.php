<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Lib\WxMessage;

class ReserveController extends Controller
{
    //
    public function store(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);
        
        $data = DB::table('customer_reserve')->where('status', $request->query('status', 1));
        
        $sql = str_replace('?', $request->query('status', 1), $data->toSql());
        
        
        if ($search = $request->query('search', false)) {
            $sql .= " and (fid like \"%$search%\" or rid like \"%$search%\" or telephone like \"%$search%\")";
        }
    
        $count = count(DB::select($sql));
        
        $sql .= ' order by status asc, id desc ';
        
        $min = ($page - 1) * $limit;
        $max = $page * $limit;
        
        $sql .= "limit $min, $max";
        
        $data = DB::select($sql);
            
        return [
            'code' => 0,
            'count' => DB::table('customer_reserve')->count(),
            'data' => $data,
            'message' => 'ok'
        ];  
    }
    
    public function update(Request $request)
    {
        if ($request->query('action', false) === 'finish') {
            $where = $request->input();
            unset($where['action']);
            
            $openid = DB::table('customer')->where('fid', $where['fid'])->first()->openid;
            $data = DB::table('customer_reserve')->where('rid', $where['rid'])->first();
            try {
                $wx = new WxHelper();

                if ($wx->sendMsg($openid, 'LLLF0xLhurORvO57XXV7JOwaQIvlKPRc5PucQrUpJ74', [
                    "yytime" => ['value' => $data->rtime ],
                    "yyname" => ['value' => $data->username ],
                    "yytele" => ['value' => $data->telephone ]
                ])) {
                    if (DB::table('customer_reserve')->where($where)->update(['status' => 2, 'updated_at' => date('Y-m-d H:i:s')])) {
                        return ['code' => 3001, 'message' => '确认成功'];
                    } else {
                        return ['code' => 3002, 'message' => '确认失败'];
                    }
                } else {
                    return ['code' => 3002, 'message' => '确认失败'];
                }
            } catch (\Exception $e) {
                return ['code' => 3002, 'message' => $e->getMessage() ];
            }
        } else {
            $rid = $request->query('id', -1);
        
            $params = $request->input();
            unset($params['id']);
            if (DB::table('customer_reserve')->where('rid', $rid)->update($params)) {
                return ['code' => 3001, 'message' => '修改成功'];
            } else {
                return ['code' => 3002, 'message' => '修改失败'];
            }
        }
    }
}
