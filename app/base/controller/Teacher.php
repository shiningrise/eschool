<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\TeacherModel;
use app\base\service\TeacherService;
use app\base\validate\TeacherValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Teacher extends BaseController
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
        $data = TeacherService::list($where, $page, $limit, $order);

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
        validate(TeacherValidate::class)->scene('info')->check($param);
        $data = TeacherService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("qy_userid", type="string", default="", desc="企业号ID")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("is_atbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("is_atschool", type="string", default="", desc="在校？")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("username", type="string", default="", desc="用户名")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['qy_userid'] 			= Request::param('qy_userid/s', '');
        $param['tel'] 			= Request::param('tel/s', '');
        $param['remark'] 			= Request::param('remark/s', '');
        $param['is_atbook'] 			= Request::param('is_atbook/s', '');
        $param['is_atschool'] 			= Request::param('is_atschool/s', '');
        $param['sort'] 			= Request::param('sort/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['username'] 			= Request::param('username/s', '');
        validate(TeacherValidate::class)->scene('add')->check($param);
        $data = TeacherService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("qy_userid", type="string", default="", desc="企业号ID")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("is_atbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("is_atschool", type="string", default="", desc="在校？")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("username", type="string", default="", desc="用户名")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['qy_userid']			= Request::param('qy_userid/s', '');
        $param['tel']			= Request::param('tel/s', '');
        $param['remark']			= Request::param('remark/s', '');
        $param['is_atbook']			= Request::param('is_atbook/s', '');
        $param['is_atschool']			= Request::param('is_atschool/s', '');
        $param['sort']			= Request::param('sort/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['username']			= Request::param('username/s', '');

        validate(TeacherValidate::class)->scene('edit')->check($param);

        $data = TeacherService::edit($param);

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

        validate(TeacherValidate::class)->scene('del')->check($param);

        $data = TeacherService::del($param['id']);

        return success($data);
    }
}

