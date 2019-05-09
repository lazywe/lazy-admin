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
                'uri'            => '',
                'roles'          => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 3,
                'title'          => '管理员',
                'icon'           => 'fa-users',
                'uri'            => 'users',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 4,
                'title'          => '角色',
                'icon'           => 'fa-user',
                'uri'            => 'roles',
                'roles'         => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 5,
                'title'          => '权限',
                'icon'           => 'fa-ban',
                'uri'            => 'permissions',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
            [
                'parent_id'      => 1,
                'order'          => 6,
                'title'          => '菜单管理',
                'icon'           => 'fa-bars',
                'uri'            => 'menu',
                'roles'     => 'administrator',
                'created_at'     => date("Y-m-d H:i:s"),
                'updated_at'     => date("Y-m-d H:i:s")
            ],
        ]);

        // create permissions
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-user-index', 'title' => '管理员']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-user-create', 'title' => '管理员创建']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-user-update', 'title' => '管理员修改']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-user-delete', 'title' => '管理员删除']);

        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-menu-index', 'title' => '菜单管理']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-menu-create', 'title' => '菜单管理创建']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-menu-update', 'title' => '菜单管理修改']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-menu-delete', 'title' => '菜单管理删除']);

        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-permissions-index', 'title' => '权限管理']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-permissions-create', 'title' => '权限管理创建']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-mpermissionsenu-update', 'title' => '权限管理修改']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-permissions-delete', 'title' => '权限管理删除']);

        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-roles-index', 'title' => '角色管理']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-roles-create', 'title' => '角色管理创建']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-roles-update', 'title' => '角色管理修改']);
        Permission::create(['guard_name' => 'lazy-admin', 'name' => 'admin-roles-delete', 'title' => '角色管理删除']);

        // create roles and assign created permissions
        // 管理组
        $role = Role::create(['guard_name' => 'lazy-admin', 'name' => 'administrator', 'title' => '超级管理员']);
        $role->givePermissionTo(Permission::all());
        // 管理员附加角色
        $user->assignRole($role);
    }
}
