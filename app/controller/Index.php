<?php
namespace app\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\UserModel;
// 

class Index extends BaseController
{
    /**
     * @Apidoc\Title("管理首页")
     * @Apidoc\Header(ref="headerAdmin")
     */
    public function index()
    {
        $str='ok';
        $user = UserModel::find(1);

        //$res= $user->roles()->save(['rolename'=>'角色1']);

        // $roles = $user->roles;
        // foreach ($roles as $role) {
        //     $str = $role->rolename . $str .'<br/>';
        // }
        return success($user);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
