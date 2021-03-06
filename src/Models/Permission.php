<?php

namespace Lazy\Admin\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use Connection;

    public $guard_name = 'lazy-admin';
}
