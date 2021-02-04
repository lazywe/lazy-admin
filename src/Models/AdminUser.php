<?php

namespace Lazy\Admin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use HasRoles, Connection;

    // ...
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'guard_name',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * 自定义table
     *
     * @return void
     */
    protected function getCustomTableName()
    {
        return config("lazy-admin.table_names.user");
    }
}
