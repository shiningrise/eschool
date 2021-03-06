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
     * @Apidoc\Returned(ref="return"),
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="returnPaging"),
     *      @Apidoc\Returned("list", type="array", desc="数据列表")
     * )
     */
    public function list()
    {
        // $page       = Request::param('page/d', 1);
        // $limit      = Request::param('limit/d', 5);
        // $name       = Request::param('name/s', '');
        // $code       = Request::param('code/s', '');
        // $remark     = Request::param('remark/s', '');
        // $parent_id  = Request::param('parent_id/d', -1);
        // $where = [];
        // if ($name) {
        //     $where[] = ['name', 'like', '%' . $name . '%'];
        // }
        // if ($code) {
        //     $where[] = ['code', 'like', '%' . $code . '%'];
        // }
        // if ($remark) {
        //     $where[] = ['remark', 'like', '%' . $remark . '%'];
        // }
        // if ($parent_id!=-1) {
        //     $where[] = ['parent_id', '=', $parent_id];
        // }
        // $order = [];
        //$data = PermissionService::list($where, $page, $limit, $order);
        $data = PermissionService::list();
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
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Param("sort", type="int", default="0", desc="序号")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点ID")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name']        = Request::param('name/s', '');
        $param['code']        = Request::param('code/s', '');
        $param['sort']        = Request::param('sort/s', '');
        $param['parent_id']   = Request::param('parent_id/d', 0);
        validate(PermissionValidate::class)->scene('add')->check($param);
        $data = PermissionService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Param("sort", type="int", default="0", desc="序号")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点ID")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']          = Request::param('id/d', '');
        $param['name']        = Request::param('name/s', '');
        $param['code']        = Request::param('code/s', '');
        $param['sort']        = Request::param('sort/s', '');
        $param['parent_id']   = Request::param('parent_id/d', '');

        validate(PermissionValidate::class)->scene('edit')->check($param);

        $data = PermissionService::edit($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("权限删除")
     * @Apidoc\Method("POST")
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
