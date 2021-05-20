<?php
namespace app\pingjiao\controller;

use think\facade\Request;
use app\BaseController;
use app\pingjiao\model\Pingjiao_dengdiModel;
use app\pingjiao\service\Pingjiao_dengdiService;
use app\pingjiao\validate\Pingjiao_dengdiValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("评教等第")
 * @Apidoc\Group("pingjiao")
 */
class PingjiaoDengdi extends BaseController
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
        $data = Pingjiao_dengdiService::list($where, $page, $limit, $order);

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
        validate(Pingjiao_dengdiValidate::class)->scene('info')->check($param);
        $data = Pingjiao_dengdiService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="评教")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="权重")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("dm", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['pingjiao_id'] 			= Request::param('pingjiao_id/s', '');
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['quanzhong'] 			= Request::param('quanzhong/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['dm'] 			= Request::param('dm/s', '');
        validate(Pingjiao_dengdiValidate::class)->scene('add')->check($param);
        $data = Pingjiao_dengdiService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="评教")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="权重")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("dm", type="string", default="", desc="代码")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['pingjiao_id']			= Request::param('pingjiao_id/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['quanzhong']			= Request::param('quanzhong/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['dm']			= Request::param('dm/s', '');

        validate(Pingjiao_dengdiValidate::class)->scene('edit')->check($param);

        $data = Pingjiao_dengdiService::edit($param);

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

        validate(Pingjiao_dengdiValidate::class)->scene('del')->check($param);

        $data = Pingjiao_dengdiService::del($param['id']);

        return success($data);
    }
}

