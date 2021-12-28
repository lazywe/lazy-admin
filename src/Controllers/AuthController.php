<?php

namespace Lazy\Admin\Controllers;

use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Lazy\Admin\Guard;
use Lazy\Admin\Models\AdminUser;

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
        $credentials = $request->only('account', 'password');
        $validator = Validator::make($credentials, [
            'account'       => 'required',
            'password'    => 'required',
        ], [
            'account.required' => '名称/邮箱不能为空.',
            'password.required' => '密码不能为空.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        $guardName = Guard::ADMIN_GUARD;
        $where = [
            ['name', '=', $credentials['account']],
            ['guard_name', '=', $guardName]
        ];
        $orWhere = [
            ['email', '=', $credentials['account']],
            ['guard_name', '=', $guardName]
        ];
        $adminUser = AdminUser::where($where)->orWhere($orWhere)->first();
        if (empty($adminUser)) {
            return ajaxReturn(0, '账号密码错误,请重试.');
        }
        $checkPass = Hash::check($credentials['password'], $adminUser->getAuthPassword());
        if (!$checkPass) {
            return ajaxReturn(0, '账号密码错误,请重试.');
        }
        // 登录用户
        Auth::guard($guardName)->login($adminUser);
        // 跳回判断
        $previousUrl = $request->session()->get('previous_url');
        $url = route('lazy-admin.home');
        if (!empty($previousUrl)) {
            $request->session()->forget('previous_url');
            $url = $previousUrl;
        }
        return ajaxReturn(1, '成功', ['url'=>$url]);
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
            $guardName = Guard::ADMIN_GUARD;
            Auth::guard($guardName)->logout();
            return ajaxReturn(1, '退出成功', ['url'=>route('lazy-admin.home')]);
        } catch (\Exception $e) {
        }
    }
}
