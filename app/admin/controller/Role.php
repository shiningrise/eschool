<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\RoleModel;
use app\admin\service\RoleService;
use app\admin\validate\RoleValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("角色管理")
 * @Apidoc\Group("admin")
 */
class Role extends BaseController
{
    public function index()
    {
        // $user           = new User;
        // $user->username     = 'wxy';
        // $user->password    = md5('php@qq.com');
        // $user->save();
    }
    /**
     * @Apidoc\Title("角色管理")
     * @Apidoc\Param("page", type="int", default="1", desc="页码")
     * @Apidoc\Param("limit", type="int", default="10", desc="pagesize")
     * @Apidoc\Returned(ref="return"),
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="returnPaging"),
     *      @Apidoc\Returned("list", type="array", desc="数据列表")
     * )
     */
    public function list()
    {
        $page       = Request::param('page/d', 1);
        $limit      = Request::param('limit/d', 5);
        $where = [];
        $order = [];
        $data = RoleService::list($where, $page, $limit, $order);

        return success($data);
    }

    /**
     * @Apidoc\Title("角色信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(RoleValidate::class)->scene('info')->check($param);
        $data = RoleService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("角色添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("rolename", type="string", default="角色名称", desc="角色名称")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['rolename']        = Request::param('rolename/s', '');
        validate(RoleValidate::class)->scene('add')->check($param);
        $data = RoleService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("角色修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("rolename", type="string", default="角色名称", desc="角色名称")
     * @Apidoc\Param("beizhu", type="string", default="备注", desc="备注")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']    = Request::param('id/d', '');
        $param['rolename']      = Request::param('rolename/s', '');
        $param['beizhu']      = Request::param('beizhu/s', '');

        validate(RoleValidate::class)->scene('edit')->check($param);

        $data = RoleService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("角色删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(RoleValidate::class)->scene('del')->check($param);

        $data = RoleService::del($param['id']);

        return success($data);
    }
}
