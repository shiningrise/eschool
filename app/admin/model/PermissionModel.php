<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\RoleModel;

/**
 * @mixin \think\Model
 */
class PermissionModel extends Model
{
    protected $name = 'permission';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
    ];

    public function roles()
    {
        return $this->belongsToMany(RoleModel::class,'role_permission','role_id','permission_id');
    }
}
