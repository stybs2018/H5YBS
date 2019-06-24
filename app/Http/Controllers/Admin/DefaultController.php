<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Assign;

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
                
            case 'assign': 
                if (!in_array('api/admin/admin/role/assign@POST', $action)) {
                    abort(403, '无权限此操作');
                }
                $id = $request->query('id');
                $assign = DB::table('admin_assign')
                    ->select('permission as key')
                    ->where('role', $id)
                    ->get();
                $permission = DB::table('admin_permission')->get();
                $Menu = []; $Action = []; $Page = [];
                foreach($permission as $i) {
                    array_push(${$i->type}, $i);
                }
                return view('admin.auth.assign', [
                    'id' => $id,
                    'Menu' => Assign::MenuTree($Menu), 
                    'Action' => Assign::AuthTree($Action), 
                    'Page' => Assign::AuthTree($Page), 
                    'Assign' => array_column(json_decode(json_encode($assign), true), 'key')
                ]);
                break;
        }
    }
    
    //  管理员页面
    public function admin(Request $request)
    {
        $action = $request->query('action', 'store');
        
        switch ($action) {
            case 'store':
                return view('admin.auth.admin');
                break;
            case 'create':
                return view('admin.auth.admin_add', [
                        'role' => $this->roleToOption()
                    ]);
                break;
            case 'update':
                $data = DB::table('admin')
                    ->select('username', 'nickname', 'telephone', 'status', 'role' )
                    ->where('id', $request->query('id'))
                    ->first();
                return view('admin.auth.admin_upd', [
                        'id'   => $request->query('id'),
                        'role' => $this->roleToOption(),
                        'data' => $data
                    ]);
                break;
        }
    }
    
    //  客户页面
    public function customer(Request $request)
    {
        $action = $request->query('action', 'store');
        
        switch ($action) {
            case 'store':
                return view('admin.customer.store');
                break;
        }
    }
    
    //  预约页面
    public function reserve(Request $request)
    {
        $action = $request->query('action', 'store');
        
        switch ($action) {
            case 'store':
                return view('admin.reserve.store');
                break;
        }
    }
    
    private function roleToOption()
    {
        $html = '';
        foreach (DB::table('admin_role')->where('status', '>=', 0)->get() as $i) {
            $html .= "<option value=$i->id>$i->name</option>";
        }
        return $html;
    }
}
