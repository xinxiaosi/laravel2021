<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @param Request $request \
     * @return void
     */
    public function boot(Request $request)
    {
//      日志记录sql 语句 和 请求参数
        if (env('SHOW_SQL', true) === true) {
            $params = $request->all();
            DB::listen(function ($query) use ($params) {
                $bindings = $query->bindings;
                $time = $query->time;
                $sql_type = substr($query->sql, 0, 6);//sql类型 增删改查
                $slow_rule = [
                    'select' => env('SQL_SELECT_TIME', 2000),
                    'insert' => env('SQL_INSERT_TIME', 500),
                    'update' => env('SQL_UPDATE_TIME', 500),
                    'delete' => env('SQL_DELETE_TIME', 500),
                ];
                $maxTime = $slow_rule[$sql_type] ?? 100;
                if ($bindings) {
                    if ($time >= $maxTime) {
                        Log::channel('showSqlLog')->info('用户请求数据:', $params);
                        Log::channel('showSqlLog')->info(' execution time: ' . $query->time . 'ms; ' . $query->sql . ': params:' . json_encode($bindings));
                    }
                } else {
                    if ($time >= $maxTime) {
                        Log::channel('showSqlLog')->info('用户请求数据:', $params);
                        Log::channel('showSqlLog')->info(' execution time: ' . $query->time . 'ms; ' . $query->sql);
                    }
                }
            });
        }
    }

    public function register()
    {
        //$this->app->register(UserServiceProvider::class);
        //$this->app->register(OrderServiceProvider::class);
        //$this->app->register(TestServiceProvider::class);
    }
}
