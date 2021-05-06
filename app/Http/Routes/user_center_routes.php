<?php
/**
 * 用户模块
 */

//管理员管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/admin',
    'middleware' => ['response'],
], function () {
    Route::post('/login', "AdminController@login");
    Route::post('/add', "AdminController@addData")
//        ->middleware('auth:jwt')
    ;
    Route::get('/get', "AdminController@getData");
    Route::delete('/add', "AdminController@deleteData");
    Route::put('/add', "AdminController@editData");
    Route::get('/info', "AdminController@getDataInfo");
});

