<?php

namespace Lazy\Admin\Controllers;

use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 登录页面
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        return view('lazy-view::auth.login');
    }

    /**
     * 处理认证尝试
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function loginDo(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email'       => 'required',
            'password'    => 'required',
        ], [
            'email.required' => '邮箱不能为空.',
            'password.required' => '密码不能为空.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        $guardName = config('lazy-admin.guard_name');
        if (Auth::guard($guardName)->gattempt($credentials)) {
            return ajaxReturn(1, '成功', ['url'=>route('lazy-admin.home')]);
        } else {
            return ajaxReturn(0, '账号密码错误,请重试.');
        }
    }

    /**
     * 退出登录
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            $guardName = config('lazy-admin.guard_name');
            Auth::guard($guardName)->logout();
            return ajaxReturn(1, '退出成功', ['url'=>route('lazy-admin.home')]);
        } catch (\Exception $e) {
        }
    }
}
