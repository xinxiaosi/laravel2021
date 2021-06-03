<?php
/**
 * 公共路由
 */

Route::group([
    'namespace' => 'App\Http\Controllers\Common',
    'prefix' => 'common/admin',
    'middleware' => ['response'],
], function () {
    Route::post('/login', "JwtBaseController@login");
    Route::post('/logout', "JwtBaseController@logout");
    Route::post('/register', "JwtBaseController@addData");
});
