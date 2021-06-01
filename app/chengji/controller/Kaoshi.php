<?php
namespace app\chengji\controller;

use think\facade\Request;
use think\facade\Log;
use app\BaseController;
use app\chengji\model\KaoshiModel;
use app\chengji\service\KaoshiService;
use app\chengji\validate\KaoshiValidate;
use app\base\service\XuenianxueqiService;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("chengji")
 */
class Kaoshi extends BaseController
{
	/**
	 * @Apidoc\Title("学期列表")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function listXueqi()
	{
	    $data = XuenianxueqiService::listAll();
	    return success($data);
	}
	
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

		$xueqiId       = Request::param('xueqiId/d', 0);
		$xueqi = 0;
		if($xueqiId){
			$xueqi = XuenianxueqiService::getById($xueqiId);
		}
        $where = [];
        $name               = Request::param('name/s', '');
        if ($name) {
            $where[] = ['name', 'LIKE','%' .$name. '%'];
        }
        if ($xueqi) {
            $where[] = ['xueqi', '=',$xueqi['xueqi']];
			$where[] = ['xuenian', '=',$xueqi['xuenian']];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = KaoshiService::list($where, $page, $limit, $order);

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
        validate(KaoshiValidate::class)->scene('info')->check($param);
        $data = KaoshiService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("status", type="string", default="", desc="状态")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("pernum", type="string", default="", desc="前缀")
     * @Apidoc\Param("leibie", type="string", default="", desc="类别")
     * @Apidoc\Param("xueqi", type="string", default="", desc="学期")
     * @Apidoc\Param("xuenian", type="string", default="", desc="学年")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name'] 			= Request::param('name/s', '');
        $param['status'] 			= Request::param('status/s', '');
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['pernum'] 			= Request::param('pernum/s', '');
        $param['leibie'] 			= Request::param('leibie/s', '');
        $param['xueqi'] 			= Request::param('xueqi/s', '');
        $param['xuenian'] 			= Request::param('xuenian/s', '');
        validate(KaoshiValidate::class)->scene('add')->check($param);
        $data = KaoshiService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("status", type="string", default="", desc="状态")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("pernum", type="string", default="", desc="前缀")
     * @Apidoc\Param("leibie", type="string", default="", desc="类别")
     * @Apidoc\Param("xueqi", type="string", default="", desc="学期")
     * @Apidoc\Param("xuenian", type="string", default="", desc="学年")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['status']			= Request::param('status/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['pernum']			= Request::param('pernum/s', '');
        $param['leibie']			= Request::param('leibie/s', '');
        $param['xueqi']			= Request::param('xueqi/s', '');
        $param['xuenian']			= Request::param('xuenian/s', '');

        validate(KaoshiValidate::class)->scene('edit')->check($param);

        $data = KaoshiService::edit($param);

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

        validate(KaoshiValidate::class)->scene('del')->check($param);

        $data = KaoshiService::del($param['id']);

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
	    $data = KaoshiService::copy($param['id']);
	    return success($data);
	}
}

