<?php

namespace Lazy\Admin\Models;

use Lazy\Admin\Models\AdminUser;
use Illuminate\Database\Seeder;
use Lazy\Admin\Models\Role;
use Lazy\Admin\Models\Permission;
use Lazy\Admin\Models\Menus;

class DbSeeder extends Seeder
{
    /**
     * 创建基础库数据
     *
     * @return void
     */
    public function run()
    {

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user = AdminUser::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);
        Menus::insert([
            [
                'parent_id'      => 0,
                'order'          => 2,
                'title'          => '后台用户管理',
                'icon'           => 'fa-tasks',
                'uri'            => 'admin-users',
                'roles'          => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 3,
                'title'          => '管理员',
                'icon'           => '',
                'uri'            => 'users',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 4,
                'title'          => '角色',
                'icon'           => '',
                'uri'            => 'roles',
                'roles'         => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 5,
                'title'          => '权限',
                'icon'           => '',
                'uri'            => 'permissions',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 6,
                'title'          => '菜单管理',
                'icon'           => '',
                'uri'            => 'menu',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],[
                'parent_id'      => 1,
                'order'          => 2,
                'title'          => '日志管理',
                'icon'           => '',
                'uri'            => 'auth/log',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
        ]);

        // create permissions
        Permission::create(['name' => 'admin-user-index', 'title' => '管理员']);
        Permission::create(['name' => 'admin-user-create', 'title' => '管理员创建']);
        Permission::create(['name' => 'admin-user-update', 'title' => '管理员修改']);
        Permission::create(['name' => 'admin-user-delete', 'title' => '管理员删除']);

        Permission::create(['name' => 'admin-menu-index', 'title' => '菜单管理']);
        Permission::create(['name' => 'admin-menu-create', 'title' => '菜单管理创建']);
        Permission::create(['name' => 'admin-menu-update', 'title' => '菜单管理修改']);
        Permission::create(['name' => 'admin-menu-delete', 'title' => '菜单管理删除']);

        Permission::create(['name' => 'admin-permission-index', 'title' => '权限管理']);
        Permission::create(['name' => 'admin-permission-create', 'title' => '权限管理创建']);
        Permission::create(['name' => 'admin-permission-update', 'title' => '权限管理修改']);
        Permission::create(['name' => 'admin-permission-delete', 'title' => '权限管理删除']);

        Permission::create(['name' => 'admin-role-index', 'title' => '角色管理']);
        Permission::create(['name' => 'admin-role-create', 'title' => '角色管理创建']);
        Permission::create(['name' => 'admin-role-update', 'title' => '角色管理修改']);
        Permission::create(['name' => 'admin-role-delete', 'title' => '角色管理删除']);

        Permission::create(['name' => 'admin-auth-log', 'title' => '操作日志']);

        // create roles and assign created permissions
        // 管理组
        $role = Role::create(['name' => 'administrator', 'title' => '超级管理员']);
        $role->givePermissionTo(Permission::all());
        // 管理员附加角色
        $user->assignRole($role);
    }
}
