<?php

namespace Lazy\Admin\Controllers;

use Validator;
use Illuminate\Http\Request;
use Lazy\Admin\Models\Menus;
use Lazy\Admin\Models\Role;

class MenuController extends Controller
{
    /**
     * 菜单列表页面
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $list = Menus::orderBy('order', 'desc')->get()->toArray();
        $list = menu_tree_level($list);
        return view("lazy-view::menu.index", compact('list'));
    }

    /**
     * 显示菜单创建页面
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $list = Menus::orderBy('order', 'desc')->get()->toArray();
        $list = menu_tree_level($list);
        $roleList = Role::all();
        return view("lazy-view::menu.create", compact('list', 'roleList'));
    }

     /**
     * 菜单创建
     *
     * @param Request $request
     * @return void
     */
    public function createDo(Request $request)
    {
        $credentials = $request->only('title', 'uri', 'parent_id', 'order', 'icon', 'roles');
        $validator = Validator::make($credentials, [
            'title'              => 'required',
            'parent_id'          => 'required',
            'uri'                => 'required|max:50',
            'icon'                => 'max:50',
            'order'              => 'required',
            'roles'              => 'required',
        ], [
            'title.required'     => '菜单名字不能为空.',
            'parent_id.required' => '非法操作.',
            'uri.required'       => '请输入uri.',
            'order.required'     => '排序不能为空.',
            'roles.required'     => '请选择一个角色.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        try {
            $credentials['roles'] = implode(",", $credentials['roles']);
            $result = Menus::create($credentials);
            if (!$result) {
                throw new \Exception('添加失败,请重试.');
            }
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '添加成功', ['url'=>'back']);
    }

    /**
     * 显示菜单修改页面
     *
     * @param Request $request 请求
     * @param int     $id      菜单id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $data = Menus::find($id);
        $data->roles = explode(',', $data->roles);
        $list = Menus::orderBy('order', 'desc')->get()->toArray();
        $list = menu_tree_level($list, 0, 0, $id);
        $roleList = Role::all();
        return view("lazy-view::menu.update", compact('data', 'list', 'roleList'));
    }

    /**
     * 菜单修改
     *
     * @param Request $request
     * @return void
     */
    public function updateDo(Request $request)
    {
        $credentials = $request->only('id', 'title', 'uri', 'parent_id', 'order', 'icon', 'roles');
        $validator = Validator::make($credentials, [
            'id' => 'required',
            'title' => 'required',
            'parent_id' => 'required',
            'uri' => 'required|max:50',
            'icon' => 'max:50',
            'order' => 'required',
            'roles' => 'required',
        ], [
            'id.required' => '非法操作.',
            'title.required' => '菜单名字不能为空.',
            'parent_id.required' => '非法操作.',
            'uri.required' => '请输入uri.',
            'order.required' => '排序不能为空.',
            'roles.required' => '请选择一个角色.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        try {
            $credentials['roles'] = implode(",", $credentials['roles']);
            $menuInfo = Menus::find($credentials['id']);
            if (!$menuInfo) {
                throw new \Exception('修改失败,无此菜单.');
            }
            unset($credentials['id']);
            $result = $menuInfo->update($credentials);
            if (!$result) {
                throw new \Exception('修改失败,请重试.');
            }
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '修改成功', ['url' => 'back']);
    }

    /**
     * 删除菜单
     *
     * @param Request $request 请求
     * @param int     $id      菜单id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $adminUserInfo = Menus::where('id', $id)->first();
        if (!$adminUserInfo) {
            return ajaxReturn(1, "菜单已经不存在.");
        }
        // 是否有子菜单·
        $haveson = Menus::where('parent_id', $id)->count();
        if ($haveson>0) {
            return ajaxReturn(0, "删除失败, 存在子菜单");
        }
        $result = $adminUserInfo->delete();
        if (!$result) {
            return ajaxReturn(0, "删除失败.");
        }
        return ajaxReturn(1, '删除成功');
    }
}
