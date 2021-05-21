<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\MenuModel;
use app\admin\service\MenuService;
use app\admin\validate\MenuValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("菜单管理")
 * @Apidoc\Group("admin")
 */
class Menu extends BaseController
{
    /**
     * @Apidoc\Title("菜单管理")
     * @Apidoc\Returned(ref="return"),
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="returnPaging"),
     *      @Apidoc\Returned("list", type="array", desc="数据列表")
     * )
     */
    public function list()
    {
        $data = MenuService::list();
        return success($data);
    }

    /**
     * @Apidoc\Title("菜单信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(MenuValidate::class)->scene('info')->check($param);
        $data = MenuService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("菜单添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("name", type="string", default="", desc="菜单名称")
     * @Apidoc\Param("url", type="string", default="", desc="菜单url")
     * @Apidoc\Param("icon", type="string", default="", desc="菜单icon")
     * @Apidoc\Param("permission_code", type="string", default="", desc="权限代码")
     * @Apidoc\Param("xh", type="string", default="", desc="xh")
     * @Apidoc\Param("parent_id", type="int", default="", desc="父菜单ID")
     * @Apidoc\Param("hidden", type="int", default="", desc="隐藏？")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name']              = Request::param('name/s', '');
        $param['url']               = Request::param('url/s', '');
        $param['icon']              = Request::param('icon/s', '');
        $param['permission_code']   = Request::param('permission_code/s', '');
        $param['sort']              = Request::param('sort/d', '');
        $param['parent_id']         = Request::param('parent_id/d', '');
        $param['is_disable']        = Request::param('is_disable/d', '');
        $param['hidden']        	= Request::param('hidden/d', '');
        validate(MenuValidate::class)->scene('add')->check($param);
        $data = MenuService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("菜单修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("name", type="string", default="", desc="菜单名称")
     * @Apidoc\Param("url", type="string", default="", desc="菜单url")
     * @Apidoc\Param("icon", type="string", default="", desc="菜单icon")
     * @Apidoc\Param("permission_code", type="string", default="", desc="权限代码")
     * @Apidoc\Param("xh", type="string", default="", desc="xh")
     * @Apidoc\Param("parent_id", type="int", default="", desc="父菜单ID")
     * @Apidoc\Param("hidden", type="int", default="", desc="隐藏？")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']                = Request::param('id/d', '');
        $param['name']              = Request::param('name/s', '');
        $param['url']               = Request::param('url/s', '');
        $param['icon']              = Request::param('icon/s', '');
        $param['permission_code']   = Request::param('permission_code/s', '');
        $param['sort']              = Request::param('sort/d',0);
        $param['parent_id']         = Request::param('parent_id/d', 0);
        $param['is_disable']        = Request::param('is_disable/d', '');
		$param['hidden']        	= Request::param('hidden/d', '');
		
        validate(MenuValidate::class)->scene('edit')->check($param);

        $data = MenuService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("菜单删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(MenuValidate::class)->scene('del')->check($param);

        $data = MenuService::del($param['id']);

        return success($data);
    }
}
