<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //  获取管理组数据
    public function store(Request $request) 
    {
        $page = $request->query('page');
        $limit = $request->query('limit');
        
        $data = DB::table('admin_role')
            ->where('status', '>=', -1)
            ->orderBy('id', 'desc')
            ->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();
        
        return [
                'code' => 0,
                'message' => 'ok',
                'count' => DB::table('admin_role')->count(),
                'data' => $data
            ]; 
    }
    
    //  创建管理组
    public function create(Request $request)
    {
        try {
            if (DB::table('admin_role')->insert($request->input())) {
                return ['code' => 3001 ];
            } else {
                return ['code' => 3002, 'message' => '创建失败'];
            } 
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '创建失败'];
        }
    }
    
    //  删除管理组
    public function delete(Request $request)
    {
        $id = $request->input('role');

        try {
            if (DB::table('admin_role')->whereIn('id', $id)->delete()) {
                DB::table('admin_assign')->where('role', $id)->delete();
                return ['code' => 3001];
            } else {
                return ['code' => 3002, 'message' => '删除失败'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '删除失败'];
        }
    }
    
    //  更新管理组
    public function update(Request $request)
    {
        $id = $request->query('id');
        
        try {
            if (DB::table('admin_role')->where('id', $id)->update($request->input())) {
                return ['code' => 3001];
            } else {
                return ['code' => 3002, 'message' => '更新失败'];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '更新失败'];
        }
    }
    
    //  授权
    public function assign(Request $request)
    {
        $id = $request->input('id');
        $assign = $request->input('assign');
        $params = [];
        
        foreach ($assign as $i) {
            array_push($params, ['role' => $id, 'permission' => $i]);
        }
        
        try {
            DB::table('admin_assign')->where('role', $id)->delete();
            DB::table('admin_assign')->insert($params);
            return ['code' => 3001];
        } catch (\Illuminate\Database\QueryException $e) {
            return ['code' => 3002, 'message' => '授权失败'];
        }
       
    }
}
