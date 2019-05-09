<?php
namespace Lazy\Admin\Controllers;

use Illuminate\Http\Request;
use Lazy\Admin\Models\Role;
use Lazy\Admin\Models\Permission;
use Validator;
use DB;
use Exception;

class RoleController extends Controller
{

    /**
     * 权限列表
     *
     * @param Request $r
     * @return void
     */
    public function index(Request $r)
    {
        $list = Role::paginate(30);
        return view("lazy-view::role.index", compact('list'));
    }

    /**
     * 创建权限
     *
     * @param Request $r
     * @return void
     */
    public function create(Request $r)
    {
        // 权限
        $list = Permission::all();
        return view("lazy-view::role.create", compact('list'));
    }

    /**
     * 创建权限
     *
     * @param Request $request
     * @return void
     */
    public function createDo(Request $request)
    {
        $credentials = $request->only('title', 'name', 'permission');
        $validator = Validator::make($credentials, [
            'title' => 'required',
            'name' => [
                'required',
                'alpha_dash',
                'regex:/[a-zA-Z]+/',
                'unique:roles'
            ],
        ], [
            'title.required' => '名称不能为空.',
            'name.required' => '标识不能为空.',
            'name.unique' => '标识已经存在.',
            'name.alpha_dash' => '标识必须包含字母.',
            'name.regex' => '标识必须包含字母.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        try {
            DB::transaction(function () use ($credentials) {
                $permissions = $credentials['permission']??[];
                unset($credentials['permission']);
                $role = Role::create($credentials);
                if (!$role) {
                    throw new \Exception('添加失败,请重试.');
                }
                // 分配权限
                $result = $role->givePermissionTo($permissions);
                if (!$result) {
                    throw new \Exception('添加失败,分配权限失败.');
                }
            });
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '添加成功', ['url' => 'back']);
    }

    /**
     * 显示权限修改页面
     *
     * @param Request $request 请求
     * @param int     $id      权限id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $data = Role::find($id);
        // 权限
        $list = Permission::all();
        return view("lazy-view::role.update", compact('data', 'list'));
    }

    /**
     * 权限修改
     *
     * @param Request $request
     * @return void
     */
    public function updateDo(Request $request)
    {
        $credentials = $request->only('id', 'title', 'name', 'permission');
        $validator = Validator::make($credentials, [
            'id' => 'required',
            'title' => 'required',
            'name' => [
                'required',
                'alpha_dash',
                'regex:/[a-zA-Z]+/',
                'unique:roles,name,' . $credentials['id'],
            ],
        ], [
            'id.required'     => '非法操作.',
            'title.required'  => '名称不能为空.',
            'name.required'   => '标识不能为空.',
            'name.unique'     => '标识已经存在.',
            'name.alpha_dash' => '标识必须包含字母.',
            'name.regex'      => '标识必须包含字母.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        try {
            DB::transaction(function () use ($credentials) {
                $permissions = $credentials['permission']??[];
                unset($credentials['permission']);
                // 查找角色
                $RoleInfo = Role::find($credentials['id']);
                if (!$RoleInfo) {
                    throw new \Exception('修改失败,无此角色.');
                }
                unset($credentials['id']);
                // 修改角色
                $result = $RoleInfo->update($credentials);
                if (!$result) {
                    throw new \Exception('修改失败,请重试.');
                }
                // 分配权限
                $result = $RoleInfo->syncPermissions($permissions);
                if (!$result) {
                    throw new \Exception('修改失败,分配权限失败.');
                }
            });
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '修改成功', ['url' => 'back']);
    }

    /**
     * 删除权限
     *
     * @param Request $request 请求
     * @param int     $id      权限id
     * @return void
     */
    public function delete(Request $request, $id)
    {

        try {
            DB::transaction(function () use ($id) {
                $RoleInfo = Role::where('id', $id)->first();
                if (!$RoleInfo) {
                    return ajaxReturn(1, "权限已经不存在.");
                }
                // 删除角色所有权限
                $result = $RoleInfo->syncPermissions([]);
                if (!$result) {
                    throw new \Exception('权限删除失败');
                }
                // 删除角色
                $result = $RoleInfo->delete();
                if (!$result) {
                    throw new Exception("删除失败");
                }
            });
        } catch (Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '删除成功');
    }
}
