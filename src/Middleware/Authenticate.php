<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $guardName = config('lazy-admin.guard_name');
        if (Auth::guard($guardName)->guest()) {
            return redirect()->route('lazy-admin.login');
        }

        return $next($request);
    }
}
