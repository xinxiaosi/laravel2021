<?php

Route::group([
    'namespace' => 'App\Http\Controllers\Base',
    'prefix' => 'base/index',
    'middleware' => ['response'],
], function () {
    Route::get('/index', "IndexController@index");
});

