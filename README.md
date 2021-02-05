# lazy-admin

- 基于laravel的rbac后台系统

# Requirement

- php >= 7.0
- Composer
- laravel >= 5.8 小于5.8版本的请装1.0.8版本
- spatie/laravel-permission

# demo
![WX20200430-142527](https://user-images.githubusercontent.com/19222354/80681149-aac55800-8af2-11ea-9dc2-0473e8d16fd7.png)


# Installation

### First

```shell
    $ composer require lazywe/lazy-admin -vvv
```

### Second
- 修改config/auth.php

````
    // guards 新增如下
    'lazy-admin' => [
        'driver'   => 'session',
        'provider' => 'lazy-admin',
    ]

    // providers新增如下
    'lazy-admin' => [
        'driver' => 'eloquent',
        'model'  => Lazy\Admin\Models\AdminUser::class
    ]
````

-  注意
>  若mysql版本小于5.7.7 需要修改AppServiceProvider
>
> 新增如下
>
> use Illuminate\Support\ServiceProvider;
>
> boot方法新增如下
>
> Schema::defaultStringLength(191);
>
> **注意修改数据库配置**


### Third

- 生成配置文件命令如下

```shell
    $ php7 artisan lazy-admin:install
```

- 若自定义connection 可修改config/lazy-admin.conf 文件中的 connection
- 生成数据库命令如下

```shell
    $ php7 artisan lazy-admin:db
```

# Usage

- 打开地址 http://localhost/admin 
- 用户名 admin@gmail.com
- 密码 123456
- 注意：
- <font style="font-size:14px" color="red">后台入口前缀可以更改``lazy-admin.prefix``</font>
-    <font style="font-size:14px" color="red">所有后台路由需要使用``route()``方法，否则后台路由 ``前缀`` 可能无法动态更换</font>

## 新功能的路由规则

-  权限验证中间件  lazy-admin
- <font style="font-size:20px" color="red">注意：所有需要权限验证的路由需要使用``lazy-admin``中间件，否则...</font>

- 参考：<font style="font-size:20px" color="yellow">App\Providers\RouteServiceProvider::class</font> 文件新增如下

```php
<?php
    //... 

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        //...
        //加载后台权限组
        $this->mapAdminRoutes();
    }
    
    /**
     * 加载后台权限组
     */
    protected function mapAdminRoutes()
    {
        Route::prefix(config('lazy-admin.prefix'))
            ->middleware('web', 'lazy-admin')
            ->namespace(sprintf("%s\Admin", $this->namespace)) 
            ->group(function ($router) {
                // 后台路由文件在routes下的admin目录下 路由文件分组
                foreach (glob(base_path('routes/admin') . '/*.php') as $file) {
                    $router->group([], $file);
                }
            });
    }

    // ...

```


## 路由权限遵循 larave-premission

```php
    // 控制角色 多个 |隔开
    Route::group(['middleware' => ['role:administrator']], function () {
        //...
    });

    // 控制权限 多个 |隔开
    Route::group(['middleware' => ['permission:user-create']], function () {
        //...
    });
    // 控制角色 多个|隔开 + 控制权限 多个|隔开
    Route::group(['middleware' => ['role:administrator','permission:user-create']], function () {
        // ...
    });
    // 控制角色 或者 控制权限 多个|隔开
    Route::group(['middleware' => ['role_or_permission:administrator|user-create']], function () {
        //...
    });
```

## 模版权限

- 权限认证

```php
    @lazy_can('user-create')
        // ...
    @else_lazy_can('user-all-create')
        // ...
    @end_lazy_can
```

- 用户组权限认证

```php
    @lazy_role('administrator')
        // ...
    @else_lazy_role('editor')
        // ...
    @else_lazy_role
```

- 用户组权限认证 满足任一角色通过

```php
    @lazy_hasanyrole('administrator｜editor')
        // ...
    @else
        // ...
    @end_lazy_hasanyrole
```

- 用户组权限认证 满足所有角色通过

```php
    @lazy_hasallroles('administrator｜editor')
        // ...
    @else
        // ...
    @end_lazy_hasallroles
```

- 用户组权限认证 不满足当前角色通过

```php
    @lazy_unlessrole('administrator')
        // ...
    @else
        // ...
    @end_lazy_unlessrole
```

- [文档修改自laravel-permission](https://github.com/spatie/laravel-permission) 由于更改了后台guard name 因此使用 laravel-permission 的模版权限时需要指定`guard name` 否则不生效

### 模版 layout

- 后台公用layout

```php
    // layout
    @extends('lazy-view::layout')
    @section('content')
    // ... 自定义html
    @endsection

    // css stack
    @push('css')
        // ... css
    @endpush

    // js stack
    @push('scripts')
        // ... 自定义
    @endpush
```

- 模版其他详细功能建议看resources
