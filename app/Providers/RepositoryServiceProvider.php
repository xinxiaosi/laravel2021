<?php

namespace App\Providers;

use App\Http\Repositories\BaseRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Http\Repositories\Repository', function () {
            $router = request()->route();
            $action = $router->getAction();
            $controller = explode('@',$action['controller'] ?? '');
            $logic = str_ireplace('Controllers','Repositories',reset($controller));
            $logic = str_ireplace('Controller','Repository',$logic);
            return class_exists($logic) ? resolve($logic) : resolve(BaseRepository::class);
        });
    }
}
