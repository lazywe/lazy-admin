<?php

namespace Lazy\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Guard;
use Lazy\Admin\Models\AuthLog as ModelsAuthLog;

class AuthLog
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
        $uri = $request->path();
        $ip = $request->getClientIp();
        $method = $request->method();
        $params = $request->all();
        $userId = Auth::guard($guardName)->id()??0;
        // 创建日志
        ModelsAuthLog::create([
            'uri' => $uri,
            'ip' => $ip,
            'method' => $method,
            'params' => $params,
            'user_id' => $userId,
        ]);
        return $next($request);
    }
}
