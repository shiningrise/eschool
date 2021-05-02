<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\UserModel;

/**
 * @mixin \think\Model
 */
class MenuModel extends Model
{
    protected $name = 'menu';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
    ];

}
