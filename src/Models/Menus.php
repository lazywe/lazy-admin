<?php

namespace Lazy\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    public $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri', 'roles'];
}
