<?php

namespace Lazy\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    use Connection;

    protected $casts = [
        'params' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uri',
        'ip',
        'method',
        'params',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }

    /**
     * 自定义table
     *
     * @return void
     */
    protected function getCustomTableName()
    {
        return config("lazy-admin.table_names.log");
    }
}