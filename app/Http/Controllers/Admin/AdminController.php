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
}
