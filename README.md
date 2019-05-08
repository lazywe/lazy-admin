# lazy-admin

- 基于laravel的rbac后台系统

# Requirement

- php >= 7.0
- Composer
- larave-premission

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

- 修改数据库配置后
- 执行artisan, 安装依赖

````
$ php7 artisan lazy-admin:install
````

# Usage

- 打开地址 http://localhost/admin