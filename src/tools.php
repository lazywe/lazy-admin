<?php

use Lazy\Admin\Guard;
use Lazy\Admin\Models\Menus;

if (!function_exists('lazy_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function lazy_asset($path)
    {
        $path = sprintf("/vendor/lazy-admin/%s", $path);
        return URL::asset($path);
    }
}

if (!function_exists('adminMenus')) {

    /**
     * 后台菜单
     *
     * @return void
     */
    function adminMenus()
    {
        $menus = Menus::orderBy('order', 'desc')->get()->toArray();
        $menus = adminMenuTree($menus);
        return $menus;
    }
}

if (!function_exists('adminMenuTree')) {
    /**
     * @param array $menus 菜单节点
     * @param int   $pid 父亲id
     *
     * @return string
     */
    function adminMenuTree($menus, $pid = 0)
    {
        $guardName = Guard::ADMIN_GUARD;
        $arrs = [];
        foreach ($menus as $v) {
            // 是否有权限， 个别权限可以无限制访问
            if (!Auth::guard($guardName)->user()->hasAnyRole(config("lazy-admin.super-role", "administrator"))) {
                if (!Auth::guard($guardName)->user()->hasAnyRole(explode(',', $v['roles']))) {
                    continue;
                }
            }
            if ($v['parent_id'] == $pid) {
                $v['son'] = adminMenuTree($menus, $v['id']);
                $arrs[] = $v;
            }
        }
        return $arrs;
    }
}


if (!function_exists('menu_tree_level')) {

    /**
     * @param array $menus   菜单节点
     * @param int   $pid     父亲id
     * @param int   $level   菜单层级
     * @param int   $nosonid 无子列表id
     *
     * @return Collection
     */
    function menu_tree_level($menus, $pid = 0, $level = 0, $nosonid = 0)
    {
        $arrs = collect();
        foreach ($menus as $v) {
            if ($nosonid == $v['id']) {
                continue;
            }
            if ($v['parent_id'] == $pid) {
                $v['level'] = $level;
                $next = menu_tree_level($menus, $v['id'], ($v['level']+1), $nosonid);
                $v['next_count'] = $next->count();
                $arrs->push($v);
                $arrs = $arrs->merge($next);
            }
        }
        return $arrs;
    }
}


if (!function_exists('lazy_url')) {

    /**
     * @param string $path   菜单地址
     * @param string $params 参数
     *
     * @return string
     */
    function lazy_url($path, $params = [])
    {
        $prefix = config('lazy-admin.prefix');
        $uri = sprintf('%s/%s', $prefix, $path);
        return url($uri, $params);
    }
}


if (!function_exists('ajaxReturn')) {

    /**
     * ajax返回
     *
     * @param integer $status 1成功。0失败
     * @param string  $info   成功/失败信息
     * @param array   $data   自定义数据
     * @return void
     */
    function ajaxReturn($status = 1, $info = '', $data = [])
    {
        return response()->json(
            [
                'status' => $status,
                'info'   => $info?:($status==1?'success':'error'),
                'data'   => $data,
            ]
        );
    }
}

if (!function_exists('adminUser')) {

    /**
     * 后台用户
     *
     * @return void
     */
    function lazyAdminUser()
    {
        return auth(Guard::ADMIN_GUARD)->user();
    }
}
