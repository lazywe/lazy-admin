<?php

namespace Lazy\Admin\Controllers;

use Lazy\Admin\Models\Menus;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $menus = Menus::orderBy('order', 'desc')->get()->toArray();
        $menus = $this->menuTree($menus);
        return view('lazy-view::home', compact('menus'));
    }


    /**
     * @param array $menus 菜单节点
     * @param int   $pid 父亲id
     *
     * @return string
     */
    public function menuTree($menus, $pid = 0)
    {
        $guardName = config('lazy-admin.guard_name');
        $arrs = [];
        foreach ($menus as $v) {
            // 是否有权限， 个别权限可以无限制访问
            if (!Auth::guard($guardName)->user()->hasAnyRole(config("lazy-admin.super-role", "administrator"))) {
                if (!Auth::guard($guardName)->user()->hasAnyRole(explode(',', $v['roles']))) {
                    continue;
                }
            }
            if ($v['parent_id'] == $pid) {
                $v['son'] = $this->menuTree($menus, $v['id']);
                $arrs[] = $v;
            }
        }
        return $arrs;
    }

    /**
     * 首页
     *
     * @return void
     */
    public function index()
    {
        return '<b>Hello! Lazy Admin</b>';
    }
}
