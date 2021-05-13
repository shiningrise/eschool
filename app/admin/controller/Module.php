<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\ModuleModel;
use app\admin\service\ModuleService;
use app\admin\validate\ModuleValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("管理")
 * @Apidoc\Group("admin")
 */
class Module extends BaseController
{
    /**
     * @Apidoc\Title("列表")
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

        $name               = Request::param('name/s', '');
        $url                = Request::param('url/s', '');
        $permission_code    = Request::param('permission_code/s', '');

        $where = [];
        if ($name) {
            $where[] = ['name', 'like', '%' . $name . '%'];
        }
        if ($url) {
            $where[] = ['url', 'like', '%' . $url . '%'];
        }
        if ($permission_code) {
            $where[] = ['permission_code', 'like', '%' . $permission_code . '%'];
        }
        $order = [];
        $data = ModuleService::list($where, $page, $limit, $order);

        return success($data);
    }

    /**
     * @Apidoc\Title("信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(ModuleValidate::class)->scene('info')->check($param);
        $data = ModuleService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("url", type="string", default="", desc="URL")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点ID")
     * @Apidoc\Param("permission_code", type="string", default="", desc="代码")
     * @Apidoc\Param("sort", type="int", default="0", desc="序号")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name']              = Request::param('name/s', '');
        $param['url']               = Request::param('url/s', '');
        $param['permission_code']   = Request::param('permission_code/s', '');
        $param['sort']              = Request::param('sort/d', '');
        $param['remark']            = Request::param('remark/s', '');
        validate(ModuleValidate::class)->scene('add')->check($param);
        $data = ModuleService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("url", type="string", default="", desc="URL")
     * @Apidoc\Param("parent_id", type="int", default="0", desc="父节点ID")
     * @Apidoc\Param("permission_code", type="string", default="", desc="代码")
     * @Apidoc\Param("sort", type="int", default="0", desc="序号")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']                = Request::param('id/d', '');
        $param['name']              = Request::param('name/s', '');
        $param['url']               = Request::param('url/s', '');
        $param['permission_code']   = Request::param('permission_code/s', '');
        $param['sort']              = Request::param('sort/d', '');
        $param['remark']            = Request::param('remark/s', '');

        validate(ModuleValidate::class)->scene('edit')->check($param);

        $data = ModuleService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(ModuleValidate::class)->scene('del')->check($param);

        $data = ModuleService::del($param['id']);

        return success($data);
    }
}
