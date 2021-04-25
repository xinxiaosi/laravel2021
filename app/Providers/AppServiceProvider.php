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
     * @param Request $request\
     * @return void
     */
    public function boot(Request $request)
    {
//      日志记录sql 语句 和 请求参数
        if(env('SHOW_SQL', true) === true){
            Log::channel('showSqlLog')->info('用户请求数据:',$request->all());
            DB::listen(function($query) {
                $bindings = $query->bindings;
                $time = $query->time;
                $sql_type = substr($query->sql, 0, 6);//sql类型 增删改查
                $slow_rule = [
                    'select' => 1500,
                    'insert' => 200,
                    'update' => 200,
                    'delete' => 200
                ];
                $maxTime = $slow_rule[$sql_type] ?? 100;
                if($bindings) {
                    $time >= $maxTime
                    && Log::channel('showSqlLog')->info(' execution time: '.$query->time.'ms; '.$query->sql.': params:'.json_encode($bindings));
                } else {
                    $time >= $maxTime
                    && Log::channel('showSqlLog')->info(' execution time: '.$query->time.'ms; '.$query->sql);
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
