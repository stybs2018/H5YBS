<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::namespace('Admin')->prefix(env('ADMIN_PREFIX', '_admin'))->group(function () {
    Route::get('captcha', 'LoginController@captcha');
    Route::view('login', 'admin.login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');
    
    Route::middleware(App\Http\Middleware\Admin::class)->group(function () {
       Route::get('/', 'DefaultController@index'); 
       Route::get('admin/role', 'DefaultController@adminRole');
    });
});