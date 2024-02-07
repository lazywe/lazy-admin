<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Guard;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guardName = Guard::ADMIN_GUARD;

        if (Auth::guard($guardName)->guest()) {
            // ajax 处理
            if ($request->ajax()) {
                // 讲当前的url设置成掉线的url
                return ajaxReturn(403, "login error.", [
                    'url' => route('lazy-admin.login')
                ]);
            }
            // 讲当前的url设置成掉线的url
            return redirect()->route('lazy-admin.login');
        }

        return $next($request);
    }
}
