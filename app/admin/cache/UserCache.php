<?php
/*
 * @Description  : 管理员缓存
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-06-12
 * @LastEditTime : 2021-04-21
 */

namespace app\admin\cache;
use think\facade\Config;
use app\admin\service\SettingService;
use app\admin\service\UserService;
use think\facade\Cache;

class UserCache
{
    /**
     * 缓存key
     *
     * @param integer $user_id 管理员id
     * 
     * @return string
     */
    public static function key($user_id)
    {
        $key = 'User:' . $user_id;

        return $key;
    }

    /**
     * 缓存设置
     *
     * @param integer $user_id 管理员id
     * @param array   $user    管理员信息
     * @param integer $ttl           有效时间（秒）
     * 
     * @return bool
     */
    public static function set($user_id, $user, $ttl = 0)
    {
        $key = self::key($user_id);
        $val = $user;
        if (empty($ttl)) {
            $ttl     = Config::get('admin.token.exp') * 3600;
        }

        $res = Cache::set($key, $val, $ttl);

        return $res;
    }

    /**
     * 缓存获取
     *
     * @param integer $user_id 管理员id
     * 
     * @return array 管理员信息
     */
    public static function get($user_id)
    {
        $key = self::key($user_id);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param integer $user_id 管理员id
     * 
     * @return bool
     */
    public static function del($user_id)
    {
        $key = self::key($user_id);
        $res = Cache::delete($key);

        return $res;
    }

    /**
     * 缓存更新
     *
     * @param integer $user_id 管理员id
     * 
     * @return bool
     */
    public static function upd($user_id)
    {
        $old = UserService::info($user_id);

        self::del($user_id);

        $new = UserService::info($user_id);

        unset($new['token']);

        $user = array_merge($old, $new);

        $res = self::set($user_id, $user);

        return $res;
    }
}
