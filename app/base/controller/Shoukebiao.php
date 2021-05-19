<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\ShoukebiaoModel;
use app\base\service\ShoukebiaoService;
use app\base\validate\ShoukebiaoValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Shoukebiao extends BaseController
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
        $data = ShoukebiaoService::list($where, $page, $limit, $order);

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
        validate(ShoukebiaoValidate::class)->scene('info')->check($param);
        $data = ShoukebiaoService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['teacher_id'] 			= Request::param('teacher_id/s', '');
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['banji_id'] 			= Request::param('banji_id/s', '');
        validate(ShoukebiaoValidate::class)->scene('add')->check($param);
        $data = ShoukebiaoService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['teacher_id']			= Request::param('teacher_id/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['banji_id']			= Request::param('banji_id/s', '');

        validate(ShoukebiaoValidate::class)->scene('edit')->check($param);

        $data = ShoukebiaoService::edit($param);

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

        validate(ShoukebiaoValidate::class)->scene('del')->check($param);

        $data = ShoukebiaoService::del($param['id']);

        return success($data);
    }
}

