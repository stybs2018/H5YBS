<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
}
