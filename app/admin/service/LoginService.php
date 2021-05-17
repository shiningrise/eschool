<?php
/*
 * @Description  : 登录退出
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-05
 * @LastEditTime : 2021-04-10
 */

namespace app\admin\service;

use think\facade\Db;
use app\admin\cache\UserCache;
use app\admin\cache\VerifyCache;
use app\admin\utils\IpInfoUtils;

class LoginService
{
    /**
     * 登录
     *
     * @param array $param 登录信息
     * 
     * @return array
     */
    public static function login($param)
    {
        $username = $param['username'];
        $password = md5($param['password']);

        $field = 'id,username,login_num';

        $where[] = ['username', '=', $username];
        $where[] = ['password', '=', $password];

        $user = Db::name('user')
            ->field($field)
            ->where($where)
            ->find();

        if (empty($user)) {
            exception('账号或密码错误');
        }

        // if ($user['is_disable'] == 1) {
        //     exception('账号已被禁用，请联系管理员');
        // }

        $ip_info = IpInfoUtils::info();

        $user_id = $user['id'];

        $update['login_ip']     = $ip_info['ip'];
        $update['login_region'] = $ip_info['region'];
        $update['login_time']   = datetime();
        $update['login_num']    = $user['login_num'] + 1;
        Db::name('user')->where('id', $user_id)->update($update);

        UserCache::del($user_id);
        $user = UserService::UserInfo($user_id);

        $data['id'] = $user_id;
        $data['token']   = $user['token'];

        VerifyCache::del($param['verify_id']);

        return $data;
    }

    /**
     * 退出
     *
     * @param integer $user_id 管理员id
     * 
     * @return array
     */
    public static function logout($id)
    {
        $update['logout_time'] = datetime();

        // Db::name('user')
        //     ->where('id', $user_id)
        //     ->update($update);

        // $update['id'] = $user_id;

        UserCache::del($id);

        return $update;
    }
}


/**
 * http get 请求
 *
 * @param string $url    请求地址
 * @param array  $header 请求头部
 *
 * @return array
 */
function http_get($url, $header = [])
{
    if (empty($header)) {
        $header = [
            "Content-type:application/json;",
            "Accept:application/json"
        ];
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);

    return $response;
}
