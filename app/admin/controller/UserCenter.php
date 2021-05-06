<?php
/*
 * @Description  : 用户个人中心
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-10-12
 * @LastEditTime : 2021-04-17
 */

namespace app\admin\controller;

use think\facade\Request;
use app\admin\validate\UserCenterValidate;
use app\admin\service\UserCenterService;
use app\admin\service\MenuService;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("用户个人中心")
 * @Apidoc\Group("admin")
 */
class UserCenter
{
    /**
     * @Apidoc\Title("我的信息")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("admin_user_id", type="int", require=true, desc="用户id")
     * @Apidoc\Returned(ref="return")
     * @Apidoc\Returned("data", type="object")
     */ 
    public function info()
    {
        $param['id'] = Request::param('id/d', '');

        validate(UserCenterValidate::class)->scene('info')->check($param);

        $data = UserCenterService::info($param['id']);

        // if ($data['is_delete'] == 1) {
        //     exception('账号信息错误，请重新登录！');
        // }

        return success($data);
    }

    /**
     * @Apidoc\Title("修改信息")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\admin\model\UserModel\edit")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['admin_user_id'] = Request::param('admin_user_id/d', '');
        $param['username']      = Request::param('username/s', '');
        $param['nickname']      = Request::param('nickname/s', '');
        $param['phone']         = Request::param('phone/s', '');
        $param['email']         = Request::param('email/s', '');

        validate(UserCenterValidate::class)->scene('edit')->check($param);

        $data = UserCenterService::edit($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改密码")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param(ref="app\admin\model\UserModel\id")
     * @Apidoc\Param("password_old", type="string", require=true, desc="原密码")
     * @Apidoc\Param("password_new", type="string", require=true, desc="新密码")
     * @Apidoc\Returned(ref="return")
     */
    public function pwd()
    {
        $param['admin_user_id'] = Request::param('admin_user_id/d', '');
        $param['password_old']  = Request::param('password_old/s', '');
        $param['password_new']  = Request::param('password_new/s', '');

        validate(UserCenterValidate::class)->scene('pwd')->check($param);

        $data = UserCenterService::pwd($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("更换头像")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\ParamType("formdata")
     * @Apidoc\Param(ref="app\admin\model\UserModel\avatar")
     * @Apidoc\Returned(ref="return")
     */
    public function avatar()
    {
        $param['admin_user_id'] = Request::param('admin_user_id/d', '');
        $param['avatar']        = Request::file('avatar_file');

        validate(UserCenterValidate::class)->scene('avatar')->check($param);

        $data = UserCenterService::avatar($param);

        return success($data);
    }

}
