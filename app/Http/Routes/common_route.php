<?php
/**
 * 公共路由
 */

/**
 * 用户登录
 */
Route::group([
    'namespace' => 'App\Http\Controllers\Common',
    'prefix' => 'common/admin',
    'middleware' => ['response'],
], function () {
    Route::post('/login', "JwtBaseController@login");//管理员登录
    Route::post('/logout', "JwtBaseController@logout");//登出
    Route::post('/register', "JwtBaseController@register");//管理员注册
});
