<?php
declare (strict_types = 1);

namespace app\admin\controller;
use hg\apidoc\annotation as Apidoc;
use app\admin\model\RoleModel;
use app\admin\model\UserModel;
/**
 * @Apidoc\Title("管理首页")
 * @Apidoc\Group("admin")
 */
class Index
{
    /**
     * @Apidoc\Title("管理首页")
     * @Apidoc\Header(ref="headerAdmin")
     */
    public function index()
    {
        $str='ok';
        $user = UserModel::find(2);

        //$res= $user->roles()->save(['rolename'=>'角色1']);

        // $roles = $user->roles;
        // foreach ($roles as $role) {
        //     $str = $role->rolename . $str .'<br/>';
        // }
        return success($user);
    }

    /**
     * @Apidoc\Title("测试多对多")
     * )
     */
    public function test()
    {
        $str='ok';
        $role = RoleModel::find(1);
        $users = $role->users;
        foreach ($users as $user) {
            $str = $user->username;
        }
        return json($role);
    }

    /**
     * @Apidoc\Title("测试多对多1")
     * )
     */
    public function test1()
    {
        $user = UserModel::find(1);
        // 仅增加管理员权限（假设管理员的角色ID是1）
        // $user->roles()->save(1);
        // // 或者
        // $role = Role::find(1);
        // $user->roles()->save($role);
        // // 批量增加关联数据
        $user->roles()->saveAll([1,2,3]);
        return success($user);
    }
}
