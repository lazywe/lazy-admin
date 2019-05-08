<?php

namespace Lazy\Admin;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    /**
     * middleware
     *
     * @var array
     */
    public $routeMiddleware = [
        'lazy-admin.auth'    => Middleware\Authenticate::class,
        'role'               => RoleMiddleware::class,
        'permission'         => PermissionMiddleware::class,
        'role_or_permission' => RoleOrPermissionMiddleware::class,
    ];

    /**
     * Group
     *
     * @var array
     */
    public $middlewareGroups = [
        'lazy-admin' => [
            'lazy-admin.auth'
        ]
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMiddleware();
        $this->bindCommands();
        $this->loadRouter();
        $this->loadResources();
        $this->publish();
        $this->isSuperAdmin();
    }

    /**
     * 某些人赋予所有权限
     *
     * @return boolean
     */
    private function isSuperAdmin()
    {
        if (!$this->app->runningInConsole()) {
            Gate::before(function ($user, $ability) {
                return $user->hasRole(config("lazy-admin.super-role", "administrator")) ? true : null;
            });
        }
    }

    /**
     * 绑定commands
     *
     * @return void
     */
    private function bindCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Lazy\Admin\Commands\InstallCommand::class,
            ]);
        }
    }

     /**
     * load middleware.
     */
    private function loadMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->app->router->aliasMiddleware($key, $middleware);
        }
        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            $this->app->router->middlewareGroup($key, $middleware);
        }
    }

    /**
     * 加载路由
     *
     * @return void
     */
    private function loadRouter()
    {
        $this->loadRoutesFrom(__DIR__.'/router.php');
    }

    /**
     * 加载资源
     *
     * @return void
     */
    private function loadResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lazy-view');
        $this->publishes([__DIR__.'/../resources/assets' => public_path('vendor/lazy-admin')], 'lazy-admin-assets');
        $this->publishes([__DIR__.'/../config/lazy-admin.config.php' => config_path('lazy-admin.php')], 'lazy-admin-config');
    }

    /**
     * publish
     *
     * @return void
     */
    public function publish()
    {
        $this->publishes([__DIR__.'/../database' => database_path('migrations')], 'lazy-admin-migrations');
    }
}
