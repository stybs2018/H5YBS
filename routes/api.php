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
       Route::post('admin/role', 'RoleController@create');
       Route::put('admin/role', 'RoleController@update');
       Route::delete('admin/role', 'RoleController@delete');
       Route::post('admin/role/assign', 'RoleController@assign');
       Route::get('admin', 'AdminController@store');
       Route::post('admin', 'AdminController@create');
       Route::put('admin', 'AdminController@update');
       Route::delete('admin', 'AdminController@delete');
       Route::get('customer', 'CustomerController@store');
    });
});