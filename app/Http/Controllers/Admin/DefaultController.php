<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
