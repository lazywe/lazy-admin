<?php

namespace Lazy\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    use Connection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri', 'roles'];

    /**
     * 自定义table
     *
     * @return void
     */
    protected function getCustomTableName()
    {
        return config("lazy-admin.table_names.menu");
    }
}
