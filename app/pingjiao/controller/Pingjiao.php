<?php
namespace app\pingjiao\controller;

use think\facade\Request;
use app\BaseController;
use app\pingjiao\model\PingjiaoModel;
use app\pingjiao\service\PingjiaoService;
use app\pingjiao\validate\PingjiaoValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("pingjiao")
 */
class Pingjiao extends BaseController
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
        $name               = Request::param('name/s', '');
        if ($name) {
            $where[] = ['name', 'LIKE','%' .$name. '%'];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = PingjiaoService::list($where, $page, $limit, $order);

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
        validate(PingjiaoValidate::class)->scene('info')->check($param);
        $data = PingjiaoService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("isdeleted", type="string", default="", desc="删除？")
     * @Apidoc\Param("active", type="string", default="", desc="启用？")
     * @Apidoc\Param("enddate", type="string", default="", desc="结束时间")
     * @Apidoc\Param("startdate", type="string", default="", desc="开始时间")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['isdeleted'] 			= Request::param('isdeleted/s', '');
        $param['active'] 			= Request::param('active/s', '');
        $param['enddate'] 			= Request::param('enddate/s', '');
        $param['startdate'] 			= Request::param('startdate/s', '');
        $param['name'] 			= Request::param('name/s', '');
        validate(PingjiaoValidate::class)->scene('add')->check($param);
        $data = PingjiaoService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("isdeleted", type="string", default="", desc="删除？")
     * @Apidoc\Param("active", type="string", default="", desc="启用？")
     * @Apidoc\Param("enddate", type="string", default="", desc="结束时间")
     * @Apidoc\Param("startdate", type="string", default="", desc="开始时间")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['isdeleted']			= Request::param('isdeleted/s', '');
        $param['active']			= Request::param('active/s', '');
        $param['enddate']			= Request::param('enddate/s', '');
        $param['startdate']			= Request::param('startdate/s', '');
        $param['name']			= Request::param('name/s', '');

        validate(PingjiaoValidate::class)->scene('edit')->check($param);

        $data = PingjiaoService::edit($param);

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

        validate(PingjiaoValidate::class)->scene('del')->check($param);

        $data = PingjiaoService::del($param['id']);

        return success($data);
    }
	
	/**
	 * @Apidoc\Title("复制")
	 * @Apidoc\Method("POST")
	 * @Apidoc\Header(ref="headerAdmin")
	 * @Apidoc\Param("id", type="int", default="1", desc="id")
	 * @Apidoc\Returned(ref="return")
	 */
	public function copy()
	{
	    $param['id'] = Request::param('id/d', '');
	
	    validate(PingjiaoValidate::class)->scene('copy')->check($param);
	
	    $data = PingjiaoService::copy($param['id']);
	
	    return success($data);
	}
}

