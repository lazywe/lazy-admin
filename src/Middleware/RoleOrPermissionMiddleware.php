<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Guard;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware as MiddlewaresRoleOrPermissionMiddleware;

class RoleOrPermissionMiddleware extends MiddlewaresRoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        if (empty($guard)) {
            $guard = Guard::ADMIN_GUARD;
        }
        // 超级管理员过
        if (app('auth')->guard($guard)->user()->hasAnyRole(config("lazy-admin.super-role", "administrator"))) {
            return $next($request);
        }
        return parent::handle($request, $next, $roleOrPermission, $guard);
    }
}
