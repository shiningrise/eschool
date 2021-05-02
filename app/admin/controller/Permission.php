<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\RoleModel;
use app\admin\model\PermissionModel;
use app\admin\service\PermissionService;
use app\admin\validate\PermissionValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("权限管理")
 * @Apidoc\Group("admin")
 */
class Permission extends BaseController
{
    /**
     * @Apidoc\Title("权限管理")
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
        $data = PermissionService::list($where, $page, $limit, $order);

        return success($data);
    }

    /**
     * @Apidoc\Title("权限信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(PermissionValidate::class)->scene('info')->check($param);
        $data = PermissionService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("权限添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("permission_name", type="string", default="", desc="权限名称")
     * @Apidoc\Param("permission_code", type="string", default="", desc="权限代码")
     * @Apidoc\Param("xh", type="int", default="0", desc="序号")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点ID")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['permission_name']        = Request::param('permission_name/s', '');
        $param['permission_code']        = Request::param('permission_code/s', '');
        $param['xh']                     = Request::param('xh/s', '');
        $param['parent_id']              = Request::param('parent_id/d', '');
        validate(PermissionValidate::class)->scene('add')->check($param);
        $data = PermissionService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("权限修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("permission_name", type="string", default="", desc="权限名称")
     * @Apidoc\Param("permission_code", type="string", default="", desc="权限代码")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点id")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']                     = Request::param('id/d', '');
        $param['permission_name']        = Request::param('permission_name/s', '');
        $param['permission_code']        = Request::param('permission_code/s', '');
        $param['xh']                     = Request::param('xh/s', '');
        $param['parent_id']              = Request::param('parent_id/d', '');

        validate(PermissionValidate::class)->scene('edit')->check($param);

        $data = PermissionService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("权限删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(PermissionValidate::class)->scene('del')->check($param);

        $data = PermissionService::del($param['id']);

        return success($data);
    }
}
