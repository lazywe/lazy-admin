<?php

$config = [
    'middleware' => 'web',
    'prefix'     => config('lazy-admin.prefix'),
    'namespace'  => 'Lazy\Admin\Controllers'
];
Route::group($config, function ($router) {
    // 登录
    $router->get('login', "AuthController@login")->name('lazy-admin.login');
    $router->post('logindo', "AuthController@loginDo")->name('lazy-admin.logindo');
    // 权限内功能
    $router->middleware(config('lazy-admin.auth-middleware'))->group(function ($router) {
        // 管理页面
        $router->get('/', "HomeController@home")->name('lazy-admin.home');
        // 后台首页，可通过lazy-config.index自定义
        $router->get('index', "HomeController@index")->name('lazy-admin.index');
        // 管理员管理
        $router->get('users', "UserController@index")->middleware(['permission:admin-user-index'])->name('lazy-admin.user.index');
        $router->get('users/create', "UserController@create")->middleware(['permission:admin-user-create'])->name('lazy-admin.user.create');
        $router->post('users/createdo', "UserController@createDo")->middleware(['permission:admin-user-create'])->name('lazy-admin.user.createdo');
        $router->get('users/update/{id}', "UserController@update")->middleware(['permission:admin-user-update'])->name('lazy-admin.user.update');
        $router->post('users/updatedo', "UserController@updateDo")->middleware(['permission:admin-user-update'])->name('lazy-admin.user.updatedo');
        $router->delete('users/delete/{id}', "UserController@delete")->middleware(['permission:admin-user-delete'])->name('lazy-admin.user.delete');
        // 菜单管理管理
        $router->get('menu', "MenuController@index")->middleware(['permission:admin-menu-index'])->name('lazy-admin.menu.index');
        $router->get('menu/create', "MenuController@create")->middleware(['permission:admin-menu-create'])->name('lazy-admin.menu.create');
        $router->post('menu/createdo', "MenuController@createDo")->middleware(['permission:admin-menu-create'])->name('lazy-admin.menu.createdo');
        $router->get('menu/update/{id}', "MenuController@update")->middleware(['permission:admin-menu-update'])->name('lazy-admin.menu.update');
        $router->post('menu/updatedo', "MenuController@updateDo")->middleware(['permission:admin-menu-update'])->name('lazy-admin.menu.updatedo');
        $router->delete('menu/delete/{id}', "MenuController@delete")->middleware(['permission:admin-menu-delete'])->name('lazy-admin.menu.delete');

        // 权限管理
        $router->get('permissions', "PermissionController@index")->middleware(['permission:admin-permission-index'])->name('lazy-admin.permission.index');
        $router->get('permissions/create', "PermissionController@create")->middleware(['permission:admin-permission-create'])->name('lazy-admin.permission.create');
        $router->post('permissions/createdo', "PermissionController@createDo")->middleware(['permission:admin-permission-create'])->name('lazy-admin.permission.createdo');
        $router->get('permissions/update/{id}', "PermissionController@update")->middleware(['permission:admin-permission-update'])->name('lazy-admin.permission.update');
        $router->post('permissions/updatedo', "PermissionController@updateDo")->middleware(['permission:admin-permission-update'])->name('lazy-admin.permission.updatedo');
        $router->delete('permissions/delete/{id}', "PermissionController@delete")->middleware(['permission:admin-permission-delete'])->name('lazy-admin.permission.delete');

        // 角色管理
        $router->get('roles', "RoleController@index")->middleware(['permission:admin-role-index'])->name('lazy-admin.role.index');
        $router->get('roles/create', "RoleController@create")->middleware(['permission:admin-role-create'])->name('lazy-admin.role.create');
        $router->post('roles/createdo', "RoleController@createDo")->middleware(['permission:admin-role-create'])->name('lazy-admin.role.createdo');
        $router->get('roles/update/{id}', "RoleController@update")->middleware(['permission:admin-role-update'])->name('lazy-admin.role.update');
        $router->post('roles/updatedo', "RoleController@updateDo")->middleware(['permission:admin-role-update'])->name('lazy-admin.role.updatedo');
        $router->delete('roles/delete/{id}', "RoleController@delete")->middleware(['permission:admin-role-delete'])->name('lazy-admin.role.delete');

        // 退出
        $router->put('logout', "AuthController@logout")->name('lazy-admin.logout');
    });
});
