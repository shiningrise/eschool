<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\RoleModel;

/**
 * @mixin \think\Model
 */
class ModuleModel extends Model
{
    protected $name = 'module';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
    ];
}
