<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::post('login', 'LoginController@_login'); 
});