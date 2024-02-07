<?php

namespace Lazy\Admin\Controllers;

use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Lazy\Admin\Guard;
use Lazy\Admin\Models\AdminUser;

class AuthController extends Controller
{

    protected $redirectToSessionKey = 'redirect_to';

    /**
     * 登录页面
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        if (URL::current() != URL::previous()) {
            $request->session()->put($this->redirectToSessionKey, URL::previous());
        }
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
        $credentials = $request->only('account', 'password', 'referer');
        $validator = Validator::make($credentials, [
            'account'     => 'required',
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
        $url = route('lazy-admin.home');
        $redirectTo = $request->session()->get($this->redirectToSessionKey);
        if (!empty($redirectTo)) {
            $url = $redirectTo;
            $request->session()->forget($this->redirectToSessionKey);
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
