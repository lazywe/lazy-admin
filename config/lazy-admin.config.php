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
    'index' => "lazy-admin.home",
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
     * 自定义数据库链接
     */
    'connection' => "mysql",

    /**
     * 自定义table名字
     */
    'table_names' => [
        "user" => "admin_users", // 后台用户名字
        "log"  => "admin_auth_log", // 后台日志名
        "menu"  => "admin_menus" // 后台栏目名字
    ],
    /**
     * cdn更新
     */
    'timestamp' => "2019070069",
];
