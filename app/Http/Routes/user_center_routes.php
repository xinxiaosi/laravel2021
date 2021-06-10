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
    Route::post('/add', "AdminController@addAdmin");
    Route::delete('/delete', "AdminController@deleteAdmin");
    Route::put('/edit', "AdminController@editAdminAdmin");
    Route::get('/list', "AdminController@getAdminList");
    Route::get('/info', "AdminController@getAdminInfo");
});

//用户组管理
Route::group([
    'namespace' => 'App\Http\Controllers\UserCenter',
    'prefix' => 'userCenter/group',
    'middleware' => ['response', 'jwt'],
], function () {
    Route::post('/add', "GroupController@addGroup");
    Route::delete('/delete', "GroupController@deleteGroup");
    Route::put('/edit', "GroupController@editGroup");
    Route::get('/list', "GroupController@getGroupList");
    Route::get('/info', "GroupController@getGroupInfo");
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


