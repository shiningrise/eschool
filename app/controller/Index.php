<?php
namespace app\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\UserModel;
use app\admin\service\PermissionService;
use app\admin\service\MenuService;
use app\admin\service\ModuleService;

class Index extends BaseController
{
    /**
     * @Apidoc\Title("管理首页")
     * @Apidoc\Header(ref="headerAdmin")
     */
    public function index()
    {
        //$user = UserModel::find(2);
        //$data[0] = PermissionService::getPermissionCodeByUserId(1);
        //$data[1] = ModuleService::getModuleUrlByUserId(1);
        //$data[2] = MenuService::getByUserId(1);
        $data = request_pathinfo();
        return success($data);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
