<?php
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use app\admin\model\RoleModel;
use hg\apidoc\annotation\Field;
use hg\apidoc\annotation\AddField;
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

    /**
     * @Field("admin_user_id")
     */
    public function id()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,sort,is_disable,is_super,login_num,login_ip,login_time")
     */
    public function list()
    {
    }

    /**
     * @AddField("admin_token", type="string", desc="AdminToken")
     */
    public function info()
    {
    }

    /**
     * @Field("username,nickname,password,phone,email,remark,sort")
     */
    public function add()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,remark,sort")
     */
    public function edit()
    {
    }

    /**
     * @Field("admin_user_id")
     */
    public function dele()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("avatar_file", type="file", require=true, desc="头像文件")
     */
    public function avatar()
    {
    }

    /**
     * @Field("id,password")
     */
    public function pwd()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("admin_role_ids", type="array", require=true, desc="角色id,eg:[1,2]")
     * @AddField("admin_menu_ids", type="array", require=true, desc="菜单id,eg:[1,2]")
     */
    public function rule()
    {
    }

    /**
     * @Field("admin_user_id,is_disable")
     */
    public function disable()
    {
    }

    /**
     * @Field("admin_user_id,is_super")
     */
    public function super()
    {
    }

    /**
     * @Field("admin_user_id")
     * @AddField("admin_token", type="string", desc="AdminToken")
     */
    public function login()
    {
    }

    /**
     * @Field("admin_user_id,username,nickname,phone,email,sort,is_disable,is_super")
     */
    public function user()
    {
    }
}
