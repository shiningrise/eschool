<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class SettingModel extends Model
{
    protected $name = 'setting';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
    ];
}
