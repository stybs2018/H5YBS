<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DefaultController extends Controller
{
    //
    public function index()
    {
        $menu = session('permission')['Menu'];
        $admin = session('admin');
        return view('admin.index', [
                'admin'=> $admin,
                'menu' => $menu
            ]);
    }
    
    // 管理组页面
    public function adminRole(Request $request)
    {
        $action = session('permission')['Action'];
        
        switch ($request->query('action', 'store')) {
            case 'store': 
                return view('admin.auth.role');
                break;
            case 'create':
                if (!in_array('api/admin/admin/role@POST', $action)) {
                    abort(403, '无权限此操作');
                }
                return view('admin.auth.role_add');
                break;
            case 'update':
                if (!in_array('api/admin/admin/role@PUT', $action)) {
                    abort(403, '无权限此操作');
                }
                return view('admin.auth.role_upd', ['data' => 
                    DB::table('admin_role')->where('id', $request->query('id'))->first()
                ]);
                break;
        }
    }
}
