<?php

namespace Lazy\Admin\Models;

trait Connection
{

    /**
     * 初始化
     */
    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('lazy-admin.connection'));
        parent::__construct($attributes);

        if (method_exists($this, 'getCustomTableName')) {
            $this->setTable($this->getCustomTableName());
        }
    }
}
