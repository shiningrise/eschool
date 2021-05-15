<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\XuekeModel;
use app\base\service\XuekeService;
use app\base\validate\XuekeValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("学科")
 * @Apidoc\Group("base")
 */
class Xueke extends BaseController
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
        $data = XuekeService::list($where, $page, $limit, $order);

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
        validate(XuekeValidate::class)->scene('info')->check($param);
        $data = XuekeService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("ismain", type="string", default="", desc="主课？")
     * @Apidoc\Param("active", type="string", default="", desc="启用")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Param("shortname", type="string", default="", desc="简称")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['remark'] 			= Request::param('remark/s', '');
        $param['ismain'] 			= Request::param('ismain/s', '');
        $param['active'] 			= Request::param('active/s', '');
        $param['code'] 			= Request::param('code/s', '');
        $param['shortname'] 			= Request::param('shortname/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['sort'] 			= Request::param('sort/d', 0);
        validate(XuekeValidate::class)->scene('add')->check($param);
        $data = XuekeService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("ismain", type="string", default="", desc="主课？")
     * @Apidoc\Param("active", type="string", default="", desc="启用")
     * @Apidoc\Param("code", type="string", default="", desc="代码")
     * @Apidoc\Param("shortname", type="string", default="", desc="简称")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['remark']			= Request::param('remark/s', '');
        $param['ismain']			= Request::param('ismain/s', '');
        $param['active']			= Request::param('active/s', '');
        $param['code']			= Request::param('code/s', '');
        $param['shortname']			= Request::param('shortname/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['sort'] 			= Request::param('sort/d', 0);

        validate(XuekeValidate::class)->scene('edit')->check($param);

        $data = XuekeService::edit($param);

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

        validate(XuekeValidate::class)->scene('del')->check($param);

        $data = XuekeService::del($param['id']);

        return success($data);
    }
}

