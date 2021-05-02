<?php
/*
 * @Description  : 登录退出
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-03-26
 * @LastEditTime : 2021-04-17
 */

namespace app\admin\controller;

use think\facade\Request;
use app\admin\validate\VerifyValidate;
use app\admin\validate\UserValidate;
use app\admin\service\VerifyService;
use app\admin\service\LoginService;
use app\admin\service\SettingService;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("登录退出")
 * @Apidoc\Group("admin")
 */
class UserLogin
{
    /**
     * @Apidoc\Title("验证码")
     * @Apidoc\Method("GET")
     * @Apidoc\Returned(ref="return")
     * @Apidoc\Returned(ref="returnVerify")
     */
    public function verify()
    {
        $config = SettingService::info();
        $verify = $config['verify'];

        $data = VerifyService::create($verify);

        return success($data);
    }

    /**
     * @Apidoc\Title("登录")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("username", type="string", require=true, desc="账号/手机/邮箱")
     * @Apidoc\Param("password", type="string", require=true, desc="密码")
     * @Apidoc\Param(ref="paramVerify")
     * @Apidoc\Returned(ref="return")
     * @Apidoc\Returned("data", type="object", desc="返回数据")
     */
    public function login()
    {
        $param['username']    = Request::param('username/s', '');
        $param['password']    = Request::param('password/s', '');
        $param['verify_id']   = Request::param('verify_id/s', '');
        $param['verify_code'] = Request::param('verify_code/s', '');

        $config = SettingService::info();
        $verify = $config['verify'];
        if ($verify['switch']) {
            validate(VerifyValidate::class)->scene('check')->check($param);
        }

        validate(UserValidate::class)->scene('login')->check($param);

        $data = LoginService::login($param);

        return success($data, '登录成功');
    }

    /**
     * @Apidoc\Title("退出")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Returned(ref="return")
     * @Apidoc\Returned("data", type="object", desc="返回数据")
     */
    public function logout()
    {
        $param['user_id'] = user_id();

        validate(UserValidate::class)->scene('id')->check($param);

        $data = LoginService::logout($param['user_id']);

        return success($data, '退出成功');
    }
}
