<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyController extends Controller
{
    //
    public function reserve()
    {
        $user = session('user')->fid;
        
        
        
        return view('app.my.reserve');
    }
}
