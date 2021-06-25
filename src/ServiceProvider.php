<?php

namespace Lazy\Admin;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Lazy\Admin\Middleware\PermissionMiddleware;
use Lazy\Admin\Middleware\RoleMiddleware;
use Lazy\Admin\Middleware\RoleOrPermissionMiddleware;

class ServiceProvider extends LaravelServiceProvider
{

    /**
     * middleware
     *
     * @var array
     */
    public $routeMiddleware = [
        'lazy-admin.log'     => Middleware\AuthLog::class,
        'lazy-admin.auth'    => Middleware\Authenticate::class,
        'lazy-admin.menu'    => Middleware\MenuMiddleware::class,
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
            'lazy-admin.log',
            'lazy-admin.auth',
            'lazy-admin.menu'
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

    public function register()
    {
        $this->registerBladeExtensions();
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
                return $user->hasRole(config("lazy-admin.super-role", "administrator"), Guard::ADMIN_GUARD) ? true : null;
            });
        }
    }

    /**
     * 注册模版权限标签
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $defaultGuard = sprintf("'%s'",  Guard::ADMIN_GUARD);
        // dd($defaultGuard);
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) use ($defaultGuard) {
            // dd(12333);
            // can 权限认证
            $bladeCompiler->directive('lazy_can', function ($arguments) use ($defaultGuard) {
                list($role, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasPermissionTo({$role})): ?>";
            });
            $bladeCompiler->directive('else_lazy_can', function ($arguments) use ($defaultGuard)  {
                list($role, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasPermissionTo({$role})): ?>";
            });
            $bladeCompiler->directive('end_lazy_can', function () {
                return '<?php endif; ?>';
            });

            // 角色认证
            $bladeCompiler->directive('lazy_role', function ($arguments) use ($defaultGuard) {
                list($role, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('else_lazy_role', function ($arguments) use ($defaultGuard)  {
                list($role, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('end_lazy_role', function () {
                return '<?php endif; ?>';
            });

            // 多个角色认证 多个 | 隔开
            $bladeCompiler->directive('lazy_hasanyrole', function ($arguments) use ($defaultGuard)  {
                list($roles, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAnyRole({$roles})): ?>";
            });
            $bladeCompiler->directive('end_lazy_hasanyrole', function () {
                return '<?php endif; ?>';
            });

            // 包含所有角色
            $bladeCompiler->directive('lazy_hasallroles', function ($arguments) use ($defaultGuard)  {
                list($roles, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAllRoles({$roles})): ?>";
            });
            $bladeCompiler->directive('end_lazy_hasallroles', function () {
                return '<?php endif; ?>';
            });

            // 不包含当前角色
            $bladeCompiler->directive('lazy_unlessrole', function ($arguments) use ($defaultGuard)  {
                list($role, $guard) = explode(',', $arguments.',');
                if (empty($guard)) {
                    $guard = $defaultGuard;
                }
                return "<?php if(!auth({$guard})->check() || !auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('end_lazy_unlessrole', function () {
                return '<?php endif; ?>';
            });
        });
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
                \Lazy\Admin\Commands\DBCommand::class,
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
        $this->publishes([__DIR__.'/../config/permission.php' => config_path('permission.php')], 'permission');
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
