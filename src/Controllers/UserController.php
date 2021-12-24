<?php

namespace Lazy\Admin\Controllers;

use Validator;
use Illuminate\Http\Request;
use Lazy\Admin\Models\AdminUser;
use Lazy\Admin\Models\Role;
use DB;
use Illuminate\Validation\Rule;
use Lazy\Admin\Guard;

class UserController extends Controller
{
    /**
     * 用户列表页面
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where[] = ['name', 'like', "%{$request->name}%"];
        }
        if (!empty($request->real_name)) {
            $where[] = ['real_name', 'like', "%{$request->real_name}%"];
        }
        if (!empty($request->email)) {
            $where[] = ['email', 'like', "%{$request->email}%"];
        }
        $list = AdminUser::where($where)->orderBy('id', 'desc')->paginate(20);
        return view("lazy-view::user.index", compact('list'));
    }

    /**
     * 显示用户创建页面
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $list = Role::all();
        return view("lazy-view::user.create", compact('list'));
    }

     /**
     * 用户创建
     *
     * @param Request $request
     * @return void
     */
    public function createDo(Request $request)
    {
        $credentials = $request->only('name','real_name', 'email', 'password', 'role');
        $validator = Validator::make($credentials, [
            'name'              => [
                'required',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("lazy-admin.table_names.user")))
            ],
            'real_name'         => 'required',
            'email'             => [
                'required',
                'email',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("lazy-admin.table_names.user")))
            ],
            'password'          => 'required|min:6',
        ], [
            'name.required'     => '名称不能为空.',
            'name.unique'     => '名称已经存在.',
            'real_name.required'  => '真实姓名不能为空.',
            'email.required'    => '邮箱不能为空.',
            'email.email'       => '邮箱格式错误.',
            'email.unique'      => '邮箱已经存在.',
            'password.required' => '密码不能为空.',
            'password.min'      => '密码最少6位.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        $credentials['password'] = bcrypt($credentials['password']);
        try {
            DB::transaction(function () use ($credentials) {
                $roles = $credentials['role']??[];
                unset($credentials['role']);
                $credentials['guard_name'] = Guard::ADMIN_GUARD;
                $user = AdminUser::create($credentials);
                if (!$user) {
                    throw new \Exception('添加失败,请重试.');
                }
                // 分配角色
                $result = $user->assignRole($roles);
                if (!$result) {
                    throw new \Exception('添加失败,分配角色失败.');
                }
            });
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '添加成功', ['url'=>'back']);
    }

    /**
     * 显示用户修改页面
     *
     * @param Request $request 请求
     * @param int     $id      用户id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $data = AdminUser::find($id);
        $list = Role::all();
        return view("lazy-view::user.update", compact('data', 'list'));
    }

    /**
     * 用户修改
     *
     * @param Request $request
     * @return void
     */
    public function updateDo(Request $request)
    {
        $credentials = $request->only('id', 'name','real_name', 'email', 'password', 'role');
        if (empty($credentials['password'])) {
            unset($credentials['password']);
        }
        $validator = Validator::make($credentials, [
            'id'                   => 'required',
            'name'                 => [
                'required',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("lazy-admin.table_names.user")))->ignore($credentials['id'])
            ],
            'real_name'            => 'required',
            'email'                => [
                'required',
                'email',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("lazy-admin.table_names.user")))->ignore($credentials['id'])
            ],
            'password'             => 'sometimes|min:6',
        ], [
            'id.required'          => '非法操作,id不能为空.',
            'name.required'        => '名称不能为空.',
            'name.unique'          => '名称已经存在.',
            'real_name.required'   => '真实姓名不能为空.',
            'email.required'       => '邮箱不能为空.',
            'email.email'          => '邮箱格式错误.',
            'email.unique'         => '邮箱已经存在.',
            'password.min'         => '密码最少6位.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        if (!empty($credentials['password'])) {
            $credentials['password'] = bcrypt($credentials['password']);
        }
        try {
            DB::transaction(function () use ($credentials) {
                $roles = $credentials['role']??[];
                unset($credentials['role']);
                $adminUserInfo = AdminUser::find($credentials['id']);
                if (!$adminUserInfo) {
                    throw new \Exception('修改失败,此用户不存在.');
                }
                // 修改用户
                $updateResult = $adminUserInfo->update($credentials);
                if (!$updateResult) {
                    throw new \Exception('修改失败,请重试.');
                }
                // 修改角色
                $result = $adminUserInfo->syncRoles($roles);
                if (!$result) {
                    throw new \Exception('添加失败,分配角色失败.');
                }
            });
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '修改成功', ['url' => 'back']);
    }

    /**
     * 删除用户
     *
     * @param Request $request 请求
     * @param int     $id      用户id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $adminUserInfo = AdminUser::where('id', $id)->first();
        if (!$adminUserInfo) {
            return ajaxReturn(1, "数据已经不存在.");
        }
        $result = $adminUserInfo->delete();
        if (!$result) {
            return ajaxReturn(0, "删除失败.");
        }
        return ajaxReturn(1, '删除成功');
    }
}
