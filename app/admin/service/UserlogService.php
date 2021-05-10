<?php
/*
 * @Description  : 日志管理
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-06
 * @LastEditTime : 2021-04-16
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\Request;
use app\admin\utils\DatetimeUtils;
use app\admin\cache\LogCache;
use app\admin\utils\IpInfoUtils;

class UserlogService
{
    /**
     * 日志管理列表
     *
     * @param array   $where 条件
     * @param integer $page  分页
     * @param integer $limit 数量
     * @param array   $order 排序
     * @param string  $field 字段
     * 
     * @return array 
     */
    public static function list($where = [], $page = 1, $limit = 10, $order = [], $field = '')
    {
        // if (empty($field)) {
        //     $field = 'id,user_id,module_id,request_method,request_ip,request_region,request_isp,response_code,response_msg,create_time';
        // }

        $where[] = ['is_delete', '=', 0];

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('userlog')
            ->where($where)
            ->count('id');

        $list = Db::name('userlog')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['username'] = '';
            $list[$k]['fullname'] = '';
            $admin_user = UserService::info($v['user_id']);
            if ($admin_user) {
                $list[$k]['username'] = $admin_user['username'];
                $list[$k]['fullname'] = $admin_user['fullname'];
            }

            $list[$k]['module_name'] = '';
            $list[$k]['module_url']  = '';
            $module = ModuleService::info($v['module_id']);
            if ($module) {
                $list[$k]['module_name'] = $module['name'];
                $list[$k]['module_url']  = $module['url'];
            }
        }

        $pages = ceil($count / $limit);

        $data['count'] = $count;
        $data['pages'] = $pages;
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['list']  = $list;

        return $data;
    }

    /**
     * 日志管理信息
     *
     * @param integer $id 日志管理id
     * 
     * @return array
     */
    public static function info($id)
    {
        $log = LogCache::get($id);
        if (empty($log)) {
            $log = Db::name('userlog')
                ->where('id', $id)
                ->find();

            if (empty($log)) {
                exception('日志管理不存在：' . $id);
            }

            if ($log['request_param']) {
                $log['request_param'] = unserialize($log['request_param']);
            }

            $log['username'] = '';
            $log['fullname'] = '';
            $user = UserService::info($log['user_id']);
            if ($user) {
                $log['username'] = $user['username'];
                $log['fullname'] = $user['fullname'];
            }

            $log['menu_name'] = '';
            $log['menu_url']  = '';
            $module = ModuleService::info($log['module_id']);
            if ($module) {
                $log['module_name'] = $module['name'];
                $log['module_url']  = $module['url'];
            }

            LogCache::set($id, $log);
        }

        return $log;
    }

    /**
     * 日志管理添加
     *
     * @param array $param 日志数据
     * 
     * @return void
     */
    public static function add($param = [])
    {
        $module         = ModuleService::info();
        $ip_info        = IpInfoUtils::info();
        $request_param  = Request::param();

        if (isset($request_param['password'])) {
            unset($request_param['password']);
        }
        if (isset($request_param['new_password'])) {
            unset($request_param['new_password']);
        }
        if (isset($request_param['old_password'])) {
            unset($request_param['old_password']);
        }

        $param['module_id']        = $module['id'];
        $param['request_ip']       = $ip_info['ip'];
        $param['request_country']  = $ip_info['country'];
        $param['request_province'] = $ip_info['province'];
        $param['request_city']     = $ip_info['city'];
        $param['request_area']     = $ip_info['area'];
        $param['request_region']   = $ip_info['region'];
        $param['request_isp']      = $ip_info['isp'];
        $param['request_param']    = serialize($request_param);
        $param['request_method']   = Request::method();
        $param['create_time']      = datetime();

        Db::name('userlog')->strict(false)
            ->insert($param);
    }
}
