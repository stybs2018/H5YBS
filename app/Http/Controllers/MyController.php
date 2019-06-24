<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyController extends Controller
{
    //
    public function reserve()
    {
        $user = session('user')->fid;
        
        $data = DB::table('customer_reserve')
            ->where('fid', $user)
            ->where('status', '!=',3)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        
        return view('app.my.reserve', [
            'data' => $data    
        ]);
    }
}
