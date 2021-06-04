<?php
/**
 * 用户管理模块
 */


//管理员管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/admin',
    'middleware' => ['response'],
], function () {
//    Route::post('/login', "AdminController@login");
    Route::post('/add', "AdminController@addData");
    Route::delete('/delete', "AdminController@deleteData");
    Route::put('/edit', "AdminController@editData");
    Route::get('/list', "AdminController@getList");
    Route::get('/info', "AdminController@getInfo");
});

//用户组管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/group',
    'middleware' => ['response', 'jwt'],
], function () {
    Route::post('/add', "GroupController@addData");
    Route::delete('/delete', "GroupController@deleteData");
    Route::put('/edit', "GroupController@editData");
    Route::get('/list', "GroupController@getList");
    Route::get('/info', "GroupController@getInfo");
});

//角色管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/role',
    'middleware' => ['response', 'jwt'],
], function () {
    Route::post('/add', "RoleController@addRole");
    Route::delete('/delete', "RoleController@deleteRole");
    Route::put('/edit', "RoleController@editRole");
    Route::get('/list', "RoleController@getRoleList");
    Route::get('/info', "RoleController@getRoleInfo");
});

//权限管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/auth',
    'middleware' => ['response', 'jwt'],
], function () {
    Route::post('/add', "AuthController@addAuth");
    Route::delete('/delete', "AuthController@deleteAuth");
    Route::put('/edit', "AuthController@editAuth");
    Route::get('/list', "AuthController@getAuthList");
    Route::get('/info', "AuthController@getAuthInfo");
});


