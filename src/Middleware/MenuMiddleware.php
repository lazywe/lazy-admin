<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Routing\RouteUri;
use Illuminate\Support\Str;
use Lazy\Admin\Guard;
use Lazy\Admin\Models\Menus;
use Spatie\Permission\Exceptions\UnauthorizedException;
use ReflectionClass;

class MenuMiddleware
{
    public function handle($request, Closure $next)
    {
        // $menus = $request->session()->get('lazy-admin-menus');
        // if (empty($menus)) {
        //     $menus = Menus::select('uri', 'roles')->get();
        //     // dd($menus);
        //     $request->session()->put('lazy-admin-menus', $menus);
        // }
        // // dd($menus);

        $path = $request->path();
        $pathArr = Str::of($path)->explode("/");
        $prefix = config('lazy-admin.prefix');
        if (!empty($prefix) && count($pathArr) > 0 && $pathArr[0] == $prefix) {
            unset($pathArr[0]);
        }
        if (empty($pathArr)) {
            return $next($request);
        }
        $sourceUri = collect($pathArr)->join("/");
        $menuInfo = Menus::where('uri', $sourceUri)->select(['roles'])->first();
        if (empty($menuInfo)) {
            return $next($request);
        }
        $guardName = Guard::ADMIN_GUARD;
        // 超级管理员过
        if (app('auth')->guard($guardName)->user()->hasAnyRole(config("lazy-admin.super-role", "administrator"))) {
            return $next($request);
        }
        // 验证当前用户，当前菜单
        $roles = Str::of($menuInfo->roles)->explode(",")->join("|");
        if (!(app('auth')->guard($guardName)->user()->hasAnyRole([$roles]))) {
            throw UnauthorizedException::forPermissions([]);
        }
        return $next($request);
    }
}
