<?php
namespace app\pingjiao\controller;

use think\facade\Request;
use app\BaseController;
use app\pingjiao\model\Pingjiao_zhibiaoModel;
use app\pingjiao\service\Pingjiao_zhibiaoService;
use app\pingjiao\validate\Pingjiao_zhibiaoValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("评教指标")
 * @Apidoc\Group("pingjiao")
 */
class PingjiaoZhibiao extends BaseController
{
    /**
     * @Apidoc\Title("列表")
     * @Apidoc\Param("page", type="int", default="1", desc="页码")
     * @Apidoc\Param("limit", type="int", default="10", desc="pagesize")
     * @Apidoc\Param("sort_field", type="string", default="", desc="sort_field")
     * @Apidoc\Param("sort_type", type="string", default="", desc="sort_type")
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
        $sort_field = Request::param('sort_field/s ', '');
        $sort_type  = Request::param('sort_type/s', '');

        $where = [];

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = Pingjiao_zhibiaoService::list($where, $page, $limit, $order);

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
        validate(Pingjiao_zhibiaoValidate::class)->scene('info')->check($param);
        $data = Pingjiao_zhibiaoService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="所属评教")
     * @Apidoc\Param("is_show", type="string", default="", desc="显示？")
     * @Apidoc\Param("fenshu", type="string", default="", desc="分数")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("dm", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['pingjiao_id'] 			= Request::param('pingjiao_id/s', '');
        $param['is_show'] 			= Request::param('is_show/s', '');
        $param['fenshu'] 			= Request::param('fenshu/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['dm'] 			= Request::param('dm/s', '');
        validate(Pingjiao_zhibiaoValidate::class)->scene('add')->check($param);
        $data = Pingjiao_zhibiaoService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="所属评教")
     * @Apidoc\Param("is_show", type="string", default="", desc="显示？")
     * @Apidoc\Param("fenshu", type="string", default="", desc="分数")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("dm", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['pingjiao_id']			= Request::param('pingjiao_id/s', '');
        $param['is_show']			= Request::param('is_show/s', '');
        $param['fenshu']			= Request::param('fenshu/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['dm']			= Request::param('dm/s', '');

        validate(Pingjiao_zhibiaoValidate::class)->scene('edit')->check($param);

        $data = Pingjiao_zhibiaoService::edit($param);

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

        validate(Pingjiao_zhibiaoValidate::class)->scene('del')->check($param);

        $data = Pingjiao_zhibiaoService::del($param['id']);

        return success($data);
    }
}

