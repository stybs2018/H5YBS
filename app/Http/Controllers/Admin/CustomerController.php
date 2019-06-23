<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    //
    public function store(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 5);
        
        $data = DB::table('customer');
        
        if ($search = $request->query('search', false)) {
            $data = $data->where('fid', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%");
        }
            
        $count = $data->count();
        
        $data = $data->offset(($page - 1) * $limit)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();
            
        return [
            'code' => 0,
            'count' => DB::table('customer')->count(),
            'data' => $data,
            'message' => 'ok'
        ];    
    }
}
