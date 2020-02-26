<?php

return [
    /**
     * 登录页面/后台主页logo
     */
    'logo' => "lazy",

    /**
     * 后台名称
     */
    'name' => "管理后台",

    /**
     * 后台入口前缀
     */
    'prefix' => "admin",

    /**
     * 后台首页地址 routeName
     */
    'index' => "lazy-admin.index",
    /**
     * 后台首页名称
     */
    'index-title' => "Dashboard",
    /**
     * 超级角色
     */
    'super-role' => [
        "administrator"
    ],
    /**
     * 权限验证中间件/可更改
     */
    'auth-middleware' => "lazy-admin",
    /**
     * 自定义数据库链接
     */
    'connection' => "mysql",

    /**
     * cdn更新
     */
    'timestamp' => "2019070069",
];
