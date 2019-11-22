# lazy-admin

- 基于laravel的rbac后台系统

# Requirement

- php >= 7.0
- Composer
- laravel >= 5.8 小于5.8版本的请装1.0.8版本
- spatie/laravel-permission

# Installation

#### First
````
$ composer require lazywe/lazy-admin -vvv
````

#### Second
- 修改config/auth.php

````
    // defaults.guard 修改如下
    'defaults' => [
        'guard' => 'lazy-admin',
        'passwords' => 'users',
    ]

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

#### 注意
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

#### Third

- 生成配置文件
````
$ php7 artisan lazy-admin:install
````

- 可修改config/lazy-admin.conf 文件中的 connection
- 生成数据库
````
$ php7 artisan lazy-admin:db
````

# Usage

- 打开地址 http://localhost/admin
- 用户名 admin@gmail.com
- 密码 123456


#### 路由权限完全遵循 larave-premission
- [前往查看](https://github.com/spatie/laravel-permission)


#### 模版 layout

- 建议看resources

````
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
````
