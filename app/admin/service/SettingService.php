<?php
/*
 * @Description  : 系统设置
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-10-12
 * @LastEditTime : 2021-04-13
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\Cache;
use app\admin\cache\UserCache;
use app\admin\cache\SettingCache;

class SettingService
{
    // 默认设置id
    private static $id = 2;
    private static $cache_key = 'cache';

    /**
     * 设置信息
     *
     * @return array
     */
    public static function info()
    {
        $id = self::$id;

        $setting = SettingCache::get($id);
        if (empty($setting)) {
            $setting = Db::name('setting')
                ->where('id', $id)
                ->find();

            if (empty($setting)) {
                $setting['id'] = $id;
                $setting['verify']           = serialize([]);
                $setting['token']            = serialize([]);
                $setting['create_time']      = datetime();
                Db::name('setting')
                    ->insert($setting);
            }

            // 验证码
            $verify = unserialize($setting['verify']);
            if (empty($verify)) {
                $verify['switch'] = false;  //开关
                $verify['curve']  = false;  //曲线
                $verify['noise']  = false;  //杂点 
                $verify['bgimg']  = false;  //背景图
                $verify['type']   = 1;      //类型：1数字，2字母，3数字字母，4算术，5中文
                $verify['length'] = 4;      //位数3-6位
                $verify['expire'] = 180;    //有效时间（秒）
            }

            // Token
            $token = unserialize($setting['token']);
            if (empty($token)) {
                $token['iss'] = 'eschool';  //签发者
                $token['exp'] = 12;          //有效时间（小时）
            }

            $setting['verify'] = serialize($verify);
            $setting['token']  = serialize($token);
            $setting['update_time']  = datetime();
            Db::name('setting')
                ->where('id', $id)
                ->update($setting);

            SettingCache::set($id, $setting);

            $setting['verify'] = $verify;
            $setting['token']  = $token;
        } else {
            $setting['verify'] = unserialize($setting['verify']);
            $setting['token']  = unserialize($setting['token']);
        }

        $cache_key = self::$cache_key;
        $cache = SettingCache::get($cache_key);
        if (empty($cache)) {
            $config = Cache::getConfig();
            if ($config['default'] == 'redis') {
                $Cache = Cache::handler();
                $cache = $Cache->info();

                $byte['type']  = 'B';
                $byte['value'] = $cache['used_memory_lua'];

                $cache['used_memory_lua_human'] = AdminUtilsService::bytetran($byte)['KB'] . 'K';
                $cache['uptime_in_days']        = $cache['uptime_in_days'] . '天';
            } elseif ($config['default'] == 'memcache') {
                $Cache = Cache::handler();
                $cache = $Cache->getstats();

                $cache['time']           = date('Y-m-d H:i:s', $cache['time']);
                $cache['uptime']         = $cache['uptime'] / (24 * 60 * 60) . ' 天';
                $cache['bytes_read']     = AdminUtilsService::bytetran(['type' => 'B', 'value' => $cache['bytes_read']])['MB'] . ' MB';
                $cache['bytes_written']  = AdminUtilsService::bytetran(['type' => 'B', 'value' => $cache['bytes_written']])['MB'] . ' MB';
                $cache['limit_maxbytes'] = AdminUtilsService::bytetran(['type' => 'B', 'value' => $cache['limit_maxbytes']])['MB'] . ' MB';
            } elseif ($config['default'] == 'wincache') {
                $Cache = Cache::handler();

                $cache['wincache_info']['wincache_fcache_meminfo'] = wincache_fcache_meminfo();
                $cache['wincache_info']['wincache_ucache_meminfo'] = wincache_ucache_meminfo();
                $cache['wincache_info']['wincache_rplist_meminfo'] = wincache_rplist_meminfo();
            }

            $cache['type'] = $config['default'];

            $cache_key = self::$cache_key;
            SettingCache::set($cache_key, $cache, 30);
        }
        $setting['cache'] = $cache;

        return $setting;
    }

    /**
     * 缓存设置
     * 
     * @param array $param  缓存参数
     *
     * @return array
     */
    public static function cache()
    {
        $user = Db::name('user')
            ->field('id')
            ->select();

        $user_cache = [];
        foreach ($user as $k => $v) {
            $user_cache = UserCache::get($v['user_id']);
            if ($user_cache) {
                $user_cache_temp['user_id'] = $user_cache['user_id'];
                $user_cache_temp['admin_token']   = $user_cache['admin_token'];
                $user_cache[] = $user_cache_temp;
            }
        }

        $res = Cache::clear();
        if (empty($res)) {
            exception();
        }

        foreach ($user_cache as $k => $v) {
            $user_new = UserService::info($v['user_id']);
            $user_new['admin_token'] = $v['admin_token'];
            UserCache::set($user_new['user_id'], $user_new);
        }

        $cache_key = self::$cache_key;
        Cache::delete($cache_key);

        $data['msg']   = '缓存已清空';
        $data['clear'] = $res;

        return $data;
    }

    /**
     * 验证码设置
     *
     * @param array $param  验证码参数
     *
     * @return array
     */
    public static function verify($param)
    {
        $id = self::$id;

        $verify['switch'] = $param['switch'];
        $verify['curve']  = $param['curve'];
        $verify['noise']  = $param['noise'];
        $verify['bgimg']  = $param['bgimg'];
        $verify['type']   = $param['type'];
        $verify['length'] = $param['length'];
        $verify['expire'] = $param['expire'];

        $update['verify']      = serialize($verify);
        $update['update_time'] = datetime();

        $res = Db::name('setting')
            ->where('id', $id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        SettingCache::del($id);

        return $verify;
    }

    /**
     * Token设置
     *
     * @param array $param token参数
     *
     * @return array
     */
    public static function token($param)
    {
        $id = self::$id;

        $token['iss'] = $param['iss'];
        $token['exp'] = $param['exp'];

        $update['token']       = serialize($token);
        $update['update_time'] = datetime();

        $res = Db::name('setting')
            ->where('id', $id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        SettingCache::del($id);

        return $token;
    }
}
