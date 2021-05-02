<?php
use think\facade\Config;
use think\facade\Request;
use app\common\service\TokenService;

/**
 * 成功返回
 *
 * @param array   $data 成功数据
 * @param string  $msg  成功提示
 * @param integer $code 成功码
 * 
 * @return json
 */
function success($data = [], string $msg = '操作成功', int $code = 200)
{
    $res['code'] = $code;
    $res['msg']  = $msg;
    $res['data'] = $data;

    return json($res);
}

/**
 * 错误返回
 *
 * @param string  $msg  错误提示
 * @param array   $err  错误数据
 * @param integer $code 错误码
 * 
 * @return json
 */
function error(string $msg = '操作失败', $err = [], int $code = 400)
{
    $res['code'] = $code;
    $res['msg']  = $msg;
    $res['err']  = $err;

    print_r(json_encode($res, JSON_UNESCAPED_UNICODE));

    exit;
}

/**
 * 抛出异常
 *
 * @param string  $msg  异常提示
 * @param integer $code 错误码
 * 
 * @return json
 */
function exception(string $msg = '操作失败', int $code = 400)
{
    throw new \think\Exception($msg, $code);
}

/**
 * 获取请求pathinfo
 * 应用/控制器/操作 
 * eg：admin/Index/index
 *
 * @return string
 */
function request_pathinfo()
{
    $request_pathinfo = app('http')->getName() . '/' . Request::pathinfo();

    return $request_pathinfo;
}

/**
 * 获取请求管理员token
 *
 * @return string
 */
function token()
{
    $token_key   = Config::get('admin.token_key');
    $token = Request::header($token_key, '');

    return $token;
}

/**
 * 获取请求管理员id
 *
 * @return integer
 */
function user_id()
{
    $user_id_key   = Config::get('admin.user_id_key');
    $user_id = Request::header($user_id_key, '');

    return $user_id;
}

/**
 * 判断管理员是否超级管理员
 *
 * @param integer $admin_user_id 管理员id
 * 
 * @return bool
 */
function user_is_super($admin_user_id = 0)
{
    if (empty($admin_user_id)) {
        return false;
    }

    $admin_super_ids = Config::get('admin.super_ids', []);
    if (empty($admin_super_ids)) {
        return false;
    }

    if (in_array($admin_user_id, $admin_super_ids)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取当前日期时间
 * format：Y-m-d H:i:s
 *
 * @return string
 */
function datetime()
{
    return date('Y-m-d H:i:s');
}