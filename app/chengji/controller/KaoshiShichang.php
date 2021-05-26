<?php
namespace app\chengji\controller;

use think\facade\Request;
use app\BaseController;
use app\chengji\model\Kaoshi_shichangModel;
use app\chengji\service\Kaoshi_shichangService;
use app\chengji\validate\Kaoshi_shichangValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("试场")
 * @Apidoc\Group("chengji")
 */
class KaoshiShichang extends BaseController
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
		$kaoshi_id      = Request::param('kaoshi_id/d', 100);
        $where[] = ['kaoshi_id','=',$kaoshi_id];

        $order = ['num'=>'asc'];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = Kaoshi_shichangService::list($where, $page, $limit, $order);

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
        validate(Kaoshi_shichangValidate::class)->scene('info')->check($param);
        $data = Kaoshi_shichangService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("renshu", type="string", default="", desc="人数")
     * @Apidoc\Param("address", type="string", default="", desc="地址")
     * @Apidoc\Param("num", type="string", default="", desc="试场号")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['kaoshi_id'] 			= Request::param('kaoshi_id/s', '');
        $param['renshu'] 			= Request::param('renshu/s', '');
        $param['address'] 			= Request::param('address/s', '');
        $param['num'] 			= Request::param('num/s', '');
        validate(Kaoshi_shichangValidate::class)->scene('add')->check($param);
        $data = Kaoshi_shichangService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("renshu", type="string", default="", desc="人数")
     * @Apidoc\Param("address", type="string", default="", desc="地址")
     * @Apidoc\Param("num", type="string", default="", desc="试场号")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['kaoshi_id']			= Request::param('kaoshi_id/s', '');
        $param['renshu']			= Request::param('renshu/s', '');
        $param['address']			= Request::param('address/s', '');
        $param['num']			= Request::param('num/s', '');

        validate(Kaoshi_shichangValidate::class)->scene('edit')->check($param);

        $data = Kaoshi_shichangService::edit($param);

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

        validate(Kaoshi_shichangValidate::class)->scene('del')->check($param);

        $data = Kaoshi_shichangService::del($param['id']);

        return success($data);
    }
}

