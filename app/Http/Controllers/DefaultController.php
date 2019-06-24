<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\WxLogin as Wx;

class DefaultController extends Controller
{
    //
    public function index(Request $request)
    {
        $rw = $request->query('rwurl', false);
        
        if ($rw) {
            return redirect($rw);    
        }
        
        return redirect('http://5g.styabos.com');
    }
}
