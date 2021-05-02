<?php
/*
 * @Description  : 系统设置缓存
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-09-07
 * @LastEditTime : 2021-04-22
 */

namespace app\admin\cache;

use think\facade\Cache;

class SettingCache
{
    /**
     * 缓存key
     *
     * @param integer $setting_id 设置id
     * 
     * @return string
     */
    public static function key($setting_id = 0)
    {
        $key = 'Setting:' . $setting_id;

        return $key;
    }

    /**
     * 缓存设置
     *
     * @param integer $setting_id 设置id
     * @param array   $admin_setting    设置信息
     * @param integer $ttl              有效时间（秒）
     * 
     * @return bool
     */
    public static function set($setting_id = 0, $admin_setting = [], $ttl = 0)
    {
        $key = self::key($setting_id);
        $val = $admin_setting;
        if (empty($ttl)) {
            $ttl = 1 * 24 * 60 * 60;
        }

        $res = Cache::set($key, $val, $ttl);

        return $res;
    }

    /**
     * 缓存获取
     *
     * @param integer $setting_id 设置id
     * 
     * @return array 设置信息
     */
    public static function get($setting_id = 0)
    {
        $key = self::key($setting_id);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param integer $setting_id 设置id
     * 
     * @return bool
     */
    public static function del($setting_id = 0)
    {
        $key = self::key($setting_id);
        $res = Cache::delete($key);

        return $res;
    }
}
