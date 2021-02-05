<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Guard;
use ReflectionClass;

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
    public function handle($request, Closure $next)
    {
        $guardName = Guard::ADMIN_GUARD;
        if (Auth::guard($guardName)->guest()) {
            // 讲当前的url设置成掉线的url
            $currentUrl = $request->url();
            $request->session()->put('previous_url', $currentUrl);
            return redirect()->route('lazy-admin.login');
        }

        return $next($request);
    }
}
