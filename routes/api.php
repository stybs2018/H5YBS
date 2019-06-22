<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::post('login', 'LoginController@_login'); 
    
    Route::middleware(App\Http\Middleware\_Admin::class)->group(function () {
       Route::get('admin/role', 'RoleController@store'); 
    });
});