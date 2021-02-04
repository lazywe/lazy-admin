<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Lazy\Admin\Guard;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middlewares\PermissionMiddleware as MiddlewaresPermissionMiddleware;

class PermissionMiddleware extends MiddlewaresPermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        if (empty($guard)) {
            $guard = Guard::ADMIN_GUARD;
        }
        // 超级管理员过
        if (app('auth')->guard($guard)->user()->hasAnyRole(config("lazy-admin.super-role", "administrator"))) {
            return $next($request);
        }
        return parent::handle($request, $next, $permission, $guard);
    }
}
