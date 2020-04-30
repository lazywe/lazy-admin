<?php

namespace Lazy\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    use Connection;

    public $table = 'admin_auth_log';

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
}