<?php

namespace Lazy\Admin\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use Connection;

    public $guard_name = 'lazy-admin';
}
