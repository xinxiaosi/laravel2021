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
    Route::delete('/delete', "AdminController@deleteData");
    Route::put('/edit', "AdminController@editData");
    Route::get('/list', "AdminController@getList");
    Route::get('/info', "AdminController@getInfo");
});

//用户组管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/group',
    'middleware' => ['response'],
], function () {
    Route::post('/add', "GroupController@addData");
    Route::delete('/delete', "GroupController@deleteData");
    Route::put('/edit', "GroupController@editData");
    Route::get('/list', "GroupController@getList");
    Route::get('/info', "GroupController@getInfo");
});
