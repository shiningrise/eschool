<?php
/*
 * @Description  : Token
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-05
 * @LastEditTime : 2021-04-12
 */

namespace app\admin\service;

use think\facade\Config;
use Firebase\JWT\JWT;
use app\admin\cache\UserCache;
class TokenService
{
    /**
     * Token生成
     * 
     * @param array $user 管理员信息
     * 
     * @return string
     */
    public static function create($user = [])
    {
        $setting = SettingService::info();
        $token   = $setting['token'];

        $key = Config::get('admin.token.key');  //密钥
        $iss = $token['iss'];                   //签发者
        $iat = time();                          //签发时间
        $nbf = time();                          //生效时间
        $exp = time() + $token['exp'] * 3600;   //过期时间

        $data = [
            'user_id'       => $user['id'],
            // 'login_time'    => $user['login_time'],
            // 'login_ip'      => $user['login_ip'],
        ];

        $payload = [
            'iss'  => $iss,
            'iat'  => $iat,
            'nbf'  => $nbf,
            'exp'  => $exp,
            'data' => $data,
        ];

        $token = JWT::encode($payload, $key);

        return $token;
    }

    /**
     * Token验证
     *
     * @param string  $token         token
     * @param integer $user_id 管理员id
     * 
     * @return json
     */
    public static function verify($token, $user_id = 0)
    {
        try {
            $key    = Config::get('admin.token.key');
            $decode = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $e) {
            exception('账号登录状态已过期', 401);
        }

        $user_id_token = $decode->data->user_id;

        if ($user_id != $user_id_token) {
            exception('账号请求信息错误', 401);
        } else {
            $user = UserCache::get($user_id);

            if (empty($user)) {
                exception('账号登录状态失效', 401);
            } else {
                if ($token != $user['token']) {
                    exception('账号已在另一处登录', 401);
                } else {
                    // if ($user['is_disable'] == 1) {
                    //     exception('账号已被禁用', 401);
                    // }
                    // if ($user['is_delete'] == 1) {
                    //     exception('账号已被删除', 401);
                    // }
                }
            }
        }
    }
}
