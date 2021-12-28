<?php

namespace Lazy\Admin\Models;

use DateTimeInterface;

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

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
