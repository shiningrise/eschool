<?php
/*
 * @Description  : 日志管理缓存
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-07-15
 * @LastEditTime : 2021-04-22
 */

namespace app\admin\cache;

use think\facade\Cache;

class ModuleCache
{
    /**
     * 缓存key
     *
     * @param integer|string $id 日志id、统计时间
     * 
     * @return string
     */
    public static function key($id = '')
    {
        $key = 'module:' . $id;

        return $key;
    }

    /**
     * 缓存设置
     *
     * @param integer|string $id 日志id、统计时间
     * @param array          $module    日志信息
     * @param integer        $ttl               有效时间（秒）
     * 
     * @return bool
     */
    public static function set($id = '', $module = [], $ttl = 0)
    {
        $key = self::key($id);
        $val = $module;

        if (empty($ttl)) {
            $ttl = 1 * 60 * 60;
        }

        $res = Cache::set($key, $val, $ttl);

        return $res;
    }

    /**
     * 缓存获取
     *
     * @param integer|string $id 日志id、统计时间
     * 
     * @return array 日志信息
     */
    public static function get($id = '')
    {
        $key = self::key($id);
        $res = Cache::get($key);

        return $res;
    }

    /**
     * 缓存删除
     *
     * @param integer|string $id 日志id、统计时间
     * 
     * @return bool
     */
    public static function del($id = '')
    {
        $key = self::key($id);
        $res = Cache::delete($key);

        return $res;
    }
}
