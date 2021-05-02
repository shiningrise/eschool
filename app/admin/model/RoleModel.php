<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\UserModel;

/**
 * @mixin \think\Model
 */
class RoleModel extends Model
{
    protected $name = 'role';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
    ];

    public function users()
    {
        return $this->belongsToMany(UserModel::class,'user_role','user_id','role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(PermissionModel::class,'role_permission','permission_id','role_id');
    }
}
