<?php
namespace app\chengji\controller;

use think\facade\Request;
use app\BaseController;
use app\chengji\model\Kaoshi_xuekeModel;
use app\chengji\service\Kaoshi_xuekeService;
use app\chengji\validate\Kaoshi_xuekeValidate;
use hg\apidoc\annotation as Apidoc;
use app\base\service\XuekeService;

/**
 * @Apidoc\Title("考试学科")
 * @Apidoc\Group("chengji")
 */
class KaoshiXueke extends BaseController
{
	public function getActiveXuekes()
	{
		$data = XuekeService::getActiveXuekes();
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

        $where = [];

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = Kaoshi_xuekeService::list($where, $page, $limit, $order);

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
        validate(Kaoshi_xuekeValidate::class)->scene('info')->check($param);
        $data = Kaoshi_xuekeService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="学科")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("havejiduka", type="string", default="", desc="有机读卡？")
     * @Apidoc\Param("zongfen", type="string", default="", desc="总分")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="权重")
     * @Apidoc\Param("shijian", type="string", default="", desc="时间")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name'] 			= Request::param('name/s', '');
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['kaoshi_id'] 			= Request::param('kaoshi_id/s', '');
        $param['havejiduka'] 			= Request::param('havejiduka/s', '');
        $param['zongfen'] 			= Request::param('zongfen/s', '');
        $param['quanzhong'] 			= Request::param('quanzhong/s', '');
        $param['shijian'] 			= Request::param('shijian/s', '');
        validate(Kaoshi_xuekeValidate::class)->scene('add')->check($param);
        $data = Kaoshi_xuekeService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="学科")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("havejiduka", type="string", default="", desc="有机读卡？")
     * @Apidoc\Param("zongfen", type="string", default="", desc="总分")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="权重")
     * @Apidoc\Param("shijian", type="string", default="", desc="时间")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['kaoshi_id']			= Request::param('kaoshi_id/s', '');
        $param['havejiduka']			= Request::param('havejiduka/s', '');
        $param['zongfen']			= Request::param('zongfen/s', '');
        $param['quanzhong']			= Request::param('quanzhong/s', '');
        $param['shijian']			= Request::param('shijian/s', '');

        validate(Kaoshi_xuekeValidate::class)->scene('edit')->check($param);

        $data = Kaoshi_xuekeService::edit($param);

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

        validate(Kaoshi_xuekeValidate::class)->scene('del')->check($param);

        $data = Kaoshi_xuekeService::del($param['id']);

        return success($data);
    }
}

