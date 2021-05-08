<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\LogModel;
use app\admin\service\LogService;
use app\admin\validate\LogValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("管理")
 * @Apidoc\Group("admin")
 */
class Log extends BaseController
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
        $data = LogService::list($where, $page, $limit, $order);

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
        validate(LogValidate::class)->scene('info')->check($param);
        $data = LogService::info($param['id']);

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

        validate(LogValidate::class)->scene('del')->check($param);

        $data = LogService::del($param['id']);

        return success($data);
    }
}
