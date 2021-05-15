<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\TeachergroupModel;
use app\base\service\TeachergroupService;
use app\base\validate\TeachergroupValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Teachergroup extends BaseController
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

        $where = [];

        $order = [];
        $data = TeachergroupService::list($where, $page, $limit, $order);

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
        validate(TeachergroupValidate::class)->scene('info')->check($param);
        $data = TeachergroupService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['remark'] 			= Request::param('remark/s', '');
        $param['sort'] 			= Request::param('sort/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['code'] 			= Request::param('code/s', '');
        validate(TeachergroupValidate::class)->scene('add')->check($param);
        $data = TeachergroupService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['remark']			= Request::param('remark/s', '');
        $param['sort']			= Request::param('sort/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['code']			= Request::param('code/s', '');

        validate(TeachergroupValidate::class)->scene('edit')->check($param);

        $data = TeachergroupService::edit($param);

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

        validate(TeachergroupValidate::class)->scene('del')->check($param);

        $data = TeachergroupService::del($param['id']);

        return success($data);
    }
}

