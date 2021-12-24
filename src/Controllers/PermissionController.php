<?php
namespace Lazy\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lazy\Admin\Models\Permission;
use Validator;

class PermissionController extends Controller
{

    /**
     * 权限列表
     *
     * @param Request $r
     * @return void
     */
    public function index(Request $r)
    {
        $list = Permission::orderBy('id', 'desc')->paginate(20);
        return view("lazy-view::permission.index", compact('list'));
    }

    /**
     * 创建权限
     *
     * @param Request $r
     * @return void
     */
    public function create(Request $r)
    {
        return view("lazy-view::permission.create");
    }

    /**
     * 创建权限
     *
     * @param Request $request
     * @return void
     */
    public function createDo(Request $request)
    {
        $credentials = $request->only('title', 'name');
        $validator = Validator::make($credentials, [
            'title'              => 'required',
            'name'               => [
                'required',
                'alpha_dash',
                'regex:/[a-zA-Z]+/',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("permission.table_names.permissions")))
            ],
        ], [
            'title.required'     => '名称不能为空.',
            'name.required'      => '标识不能为空.',
            'name.unique'        => '标识已经存在.',
            'name.alpha_dash' => '标识必须包含字母.',
            'name.regex' => '标识必须包含字母.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ajaxReturn(0, $errors->first());
        }
        try {
            $result = Permission::create($credentials);
            if (!$result) {
                throw new \Exception('添加失败,请重试.');
            }
        } catch (\Exception $e) {
            return ajaxReturn(0, $e->getMessage());
        }
        return ajaxReturn(1, '添加成功', ['url'=>'back']);
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
        $data = Permission::find($id);
        return view("lazy-view::permission.update", compact('data'));
    }

    /**
     * 权限修改
     *
     * @param Request $request
     * @return void
     */
    public function updateDo(Request $request)
    {
        $credentials = $request->only('id', 'title', 'name');
        $validator = Validator::make($credentials, [
            'id'    => 'required',
            'title' => 'required',
            'name'  => [
                'required',
                'alpha_dash',
                'regex:/[a-zA-Z]+/',
                Rule::unique(sprintf("%s.%s",config("lazy-admin.connection"), config("permission.table_names.permissions")))->ignore($credentials['id'])
            ],
        ], [
            'id.required' => '非法操作.',
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
            $permissionInfo = Permission::find($credentials['id']);
            if (!$permissionInfo) {
                throw new \Exception('修改失败,无此权限.');
            }
            unset($credentials['id']);
            $result = $permissionInfo->update($credentials);
            if (!$result) {
                throw new \Exception('修改失败,请重试.');
            }
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
        $permissionInfo = Permission::where('id', $id)->first();
        if (!$permissionInfo) {
            return ajaxReturn(1, "权限已经不存在.");
        }
        $result = $permissionInfo->delete();
        if (!$result) {
            return ajaxReturn(0, "删除失败.");
        }
        return ajaxReturn(1, '删除成功');
    }
}
