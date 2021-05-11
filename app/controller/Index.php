<?php
namespace app\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\UserModel;
use app\admin\service\PermissionService;
use app\admin\service\MenuService;

class Index extends BaseController
{
    /**
     * @Apidoc\Title("管理首页")
     * @Apidoc\Header(ref="headerAdmin")
     */
    public function index()
    {
        //$user = UserModel::find(2);
       // $data = PermissionService::getPermissionCodeByUserId(31);
        $data = MenuService::getByUserId(31);
        return success($data);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
