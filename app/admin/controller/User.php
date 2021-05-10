<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\UserModel;
use app\admin\service\UserService;
use app\admin\validate\UserValidate;
use hg\apidoc\annotation as Apidoc;
use hg\apidoc\annotation\Field;
use hg\apidoc\annotation\AddField;

/**
 * @Apidoc\Title("用户管理")
 * @Apidoc\Group("admin")
 */
class User extends BaseController
{
    /**
     * @Apidoc\Title("用户管理")
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
        $username   = Request::param('username/s', '');
        $fullname   = Request::param('fullname/s', '');
        $where = [];
        if ($username) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }
        if ($fullname) {
            $where[] = ['fullname', 'like', '%' . $fullname . '%'];
        }
        $order = [];
        $data = UserService::list($where, $page, $limit, $order);

        return success($data);
    }

    
    /**
     * @Apidoc\Title("用户信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(UserValidate::class)->scene('info')->check($param);
        $data = UserService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("用户添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("username", type="string", default="用户名", desc="用户名")
     * @Apidoc\Param("fullname", type="string", default="姓名", desc="姓名")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['username']        = Request::param('username/s', '');
        $param['fullname']        = Request::param('fullname/s', '');
        validate(UserValidate::class)->scene('add')->check($param);
        $data = UserService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("用户修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Param("username", type="string", default="用户名", desc="用户名")
     * @Apidoc\Param("fullname", type="string", default="姓名", desc="姓名")
     * @Apidoc\Param("beizhu", type="string", default="备注", desc="备注")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']    = Request::param('id/d', '');
        $param['username']      = Request::param('username/s', '');
        $param['fullname']      = Request::param('fullname/s', '');
        $param['beizhu']      = Request::param('beizhu/s', '');

        validate(UserValidate::class)->scene('edit')->check($param);

        $data = UserService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("用户删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(UserValidate::class)->scene('del')->check($param);

        $data = UserService::del($param['id']);

        return success($data);
    }

       /**
     * @Apidoc\Title("管理员重置密码")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\admin\model\UserModel\pwd")
     * @Apidoc\Returned(ref="return")
     */
    public function pwd()
    {
        $param['id'] = Request::param('id/d', '');
        $param['password']      = Request::param('password/s', '');

        validate(UserValidate::class)->scene('pwd')->check($param);

        $data = UserService::pwd($param);

        return success($data);
    }
}
