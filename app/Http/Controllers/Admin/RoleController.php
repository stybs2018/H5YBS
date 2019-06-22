<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //
    public function store(Request $request) 
    {
        $page = $request->query('page');
        $limit = $request->query('limit');
        
        $data = DB::table('admin_role')
            ->where('status', '>=', -1)
            ->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();
        
        return [
                'code' => 0,
                'message' => 'ok',
                'total' => count($data),
                'data' => $data
            ]; 
    }
}
