<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\XuenianxueqiModel;
use app\base\service\XuenianxueqiService;
use app\base\validate\XuenianxueqiValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Xuenianxueqi extends BaseController
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
        $data = XuenianxueqiService::list($where, $page, $limit, $order);

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
        validate(XuenianxueqiValidate::class)->scene('info')->check($param);
        $data = XuenianxueqiService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("createdtime", type="string", default="", desc="创建时间")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("startdate", type="string", default="", desc="开始日期")
     * @Apidoc\Param("ishidden", type="string", default="", desc="隐藏？")
     * @Apidoc\Param("iscurrent", type="string", default="", desc="当前学期？")
     * @Apidoc\Param("xueqi", type="string", default="", desc="学期")
     * @Apidoc\Param("xuenian", type="string", default="", desc="学年")
     * @Apidoc\Param("bianhao", type="string", default="", desc="编号")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name'] 			= Request::param('name/s', '');
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['startdate'] 			= Request::param('startdate/s', '');
        $param['ishidden'] 			= Request::param('ishidden/s', '');
        $param['iscurrent'] 			= Request::param('iscurrent/s', '');
        $param['xueqi'] 			= Request::param('xueqi/s', '');
        $param['xuenian'] 			= Request::param('xuenian/s', '');
        $param['bianhao'] 			= Request::param('bianhao/s', '');
        validate(XuenianxueqiValidate::class)->scene('add')->check($param);
        $data = XuenianxueqiService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("startdate", type="string", default="", desc="开始日期")
     * @Apidoc\Param("ishidden", type="string", default="", desc="隐藏？")
     * @Apidoc\Param("iscurrent", type="string", default="", desc="当前学期？")
     * @Apidoc\Param("xueqi", type="string", default="", desc="学期")
     * @Apidoc\Param("xuenian", type="string", default="", desc="学年")
     * @Apidoc\Param("bianhao", type="string", default="", desc="编号")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['startdate']			= Request::param('startdate/s', '');
        $param['ishidden']			= Request::param('ishidden/s', '');
        $param['iscurrent']			= Request::param('iscurrent/s', '');
        $param['xueqi']			= Request::param('xueqi/s', '');
        $param['xuenian']			= Request::param('xuenian/s', '');
        $param['bianhao']			= Request::param('bianhao/s', '');

        validate(XuenianxueqiValidate::class)->scene('edit')->check($param);

        $data = XuenianxueqiService::edit($param);

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

        validate(XuenianxueqiValidate::class)->scene('del')->check($param);

        $data = XuenianxueqiService::del($param['id']);

        return success($data);
    }
}

