<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\RoleModel;

/**
 * @mixin \think\Model
 */
class UserModel extends Model
{
    protected $name = 'user';
    // 设置字段信息
    protected $schema = [
        // 'id'          => 'int',
        // 'name'        => 'string',
        // 'email'       => 'string',
    ];

    public function roles()
    {
        //return $this->belongsToMany('course','stu_cour','cour_id','stu_id');
        return $this->belongsToMany(RoleModel::class,'user_role','role_id','user_id');
    }
}
