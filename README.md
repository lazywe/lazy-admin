# lazy-admin

- 基于laravel的rbac后台系统

# Requirement

- php >= 7.0
- Composer
- spatie/laravel-permission

# Installation

#### First
````
$ composer require lazywe/lazy-admin
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
- 用户名 admin@gmial.com
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
