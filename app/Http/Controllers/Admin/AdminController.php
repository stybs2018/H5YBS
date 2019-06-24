<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function store(Request $request)
    {
        $id = $request->query('id', false);
        
        if ($id) {
            
        } else {
            $page = $request->query('page');
            $limit = $request->query('limit');
            
            $data = DB::table('admin')
                ->join('admin_role', 'admin.role', '=', 'admin_role.id')
                ->select('admin.*', 'admin_role.name as rolename')
                ->offset($limit * ($page - 1))
                ->limit($limit)
                ->orderBy('admin.id', 'desc')
                ->get();
            
            return [
                    'code' => 0,
                    'message' => 'ok',
                    'count' => DB::table('admin')->count(),
                    'data' => $data
                ]; 
        }
    }
    
    // 
    public function create(Request $request)
    {
        $params = $request->input();
        $params['created_at'] = date('Y-m-d H:i:s');
        $params['updated_at'] = date('Y-m-d H:i:s');
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        try {
            if (DB::table('admin')->insert($params)) {
                return ['code' => 3001 ];
            } else {
                return ['code' => 3002, 'message' => '创建失败'];
            } 
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '创建失败'];
        }
    }
    
    //
    public function delete(Request $request)
    {
        $id = $request->input('id');

        if (DB::table('admin')->count() <= 1) {
            return ['code' => 3002, 'message' => '至少保留一个管理员账号'];
        }

        try {
            if (DB::table('admin')->whereIn('id', $id)->delete()) {
                return ['code' => 3001];
            } else {
                return ['code' => 3002, 'message' => '删除失败'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '删除失败'];
        }
    }
    
    //  
    public function update(Request $request)
    {
        $id = $request->query('id');
        
        $params = $request->input();
        $params['updated_at'] = date('Y-m-d H:i:s');
        if (isset($params['password']) && strlen($params['password']) > 0) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        } else {
            unset($params['password']);
        }
        try {
            if (DB::table('admin')->where('id', $id)->update($params)) {
                return ['code' => 3001];
            } else {
                return ['code' => 3002, 'message' => '更新失败'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '更新失败'];
        }
    }
}
