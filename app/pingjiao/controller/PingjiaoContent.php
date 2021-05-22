<?php
namespace app\pingjiao\controller;

use think\facade\Log;
use think\facade\Request;
use app\BaseController;
use app\pingjiao\model\Pingjiao_contentModel;
use app\pingjiao\service\Pingjiao_contentService;
use app\pingjiao\validate\Pingjiao_contentValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("pingjiao")
 */
class PingjiaoContent extends BaseController
{
	public function listPingjiaoState()
	{
		$pingjiao_id       = Request::param('pingjiao_id/d', 0);
		$data = Pingjiao_contentService::listPingjiaoState($pingjiao_id);
		return success($data);
	}
	public function listPingjiaoTongji()
	{
		$pingjiao_id       = Request::param('pingjiao_id/d', 0);
		$data = Pingjiao_contentService::listPingjiaoTongji($pingjiao_id);
	    return success($data);
	}
	public function save()
	{
		$data = input();
		$rtn = Pingjiao_contentService::save($data);
	    return success($rtn);
	}
	public function listShoukebiao()
	{
	    $id = user_id();
		$data = Pingjiao_contentService::listShoukebiaoByUserId($id);
	    return success($data);
	}
	
	public function getPingjiaoTable()
	{
		$data = Pingjiao_contentService::getPingjiaoTable();
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
        $data = Pingjiao_contentService::list($where, $page, $limit, $order);

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
        validate(Pingjiao_contentValidate::class)->scene('info')->check($param);
        $data = Pingjiao_contentService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("student_id", type="string", default="", desc="student_id")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="teacher_id")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="xueke_id")
     * @Apidoc\Param("banji_id", type="string", default="", desc="banji_id")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="pingjiao_id")
     * @Apidoc\Param("defen", type="string", default="", desc="defen")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="quanzhong")
     * @Apidoc\Param("fenshu", type="string", default="", desc="fenshu")
     * @Apidoc\Param("answer", type="string", default="", desc="answer")
     * @Apidoc\Param("zhibiao_dm", type="string", default="", desc="zhibiao_dm")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['student_id'] 			= Request::param('student_id/s', '');
        $param['teacher_id'] 			= Request::param('teacher_id/s', '');
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['banji_id'] 			= Request::param('banji_id/s', '');
        $param['pingjiao_id'] 			= Request::param('pingjiao_id/s', '');
        $param['defen'] 			= Request::param('defen/s', '');
        $param['quanzhong'] 			= Request::param('quanzhong/s', '');
        $param['fenshu'] 			= Request::param('fenshu/s', '');
        $param['answer'] 			= Request::param('answer/s', '');
        $param['zhibiao_dm'] 			= Request::param('zhibiao_dm/s', '');
        validate(Pingjiao_contentValidate::class)->scene('add')->check($param);
        $data = Pingjiao_contentService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("student_id", type="string", default="", desc="student_id")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="teacher_id")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="xueke_id")
     * @Apidoc\Param("banji_id", type="string", default="", desc="banji_id")
     * @Apidoc\Param("pingjiao_id", type="string", default="", desc="pingjiao_id")
     * @Apidoc\Param("defen", type="string", default="", desc="defen")
     * @Apidoc\Param("quanzhong", type="string", default="", desc="quanzhong")
     * @Apidoc\Param("fenshu", type="string", default="", desc="fenshu")
     * @Apidoc\Param("answer", type="string", default="", desc="answer")
     * @Apidoc\Param("zhibiao_dm", type="string", default="", desc="zhibiao_dm")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['student_id']			= Request::param('student_id/s', '');
        $param['teacher_id']			= Request::param('teacher_id/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['banji_id']			= Request::param('banji_id/s', '');
        $param['pingjiao_id']			= Request::param('pingjiao_id/s', '');
        $param['defen']			= Request::param('defen/s', '');
        $param['quanzhong']			= Request::param('quanzhong/s', '');
        $param['fenshu']			= Request::param('fenshu/s', '');
        $param['answer']			= Request::param('answer/s', '');
        $param['zhibiao_dm']			= Request::param('zhibiao_dm/s', '');

        validate(Pingjiao_contentValidate::class)->scene('edit')->check($param);

        $data = Pingjiao_contentService::edit($param);

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

        validate(Pingjiao_contentValidate::class)->scene('del')->check($param);

        $data = Pingjiao_contentService::del($param['id']);

        return success($data);
    }
}

