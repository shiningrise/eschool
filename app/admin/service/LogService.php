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

class LogService
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
        if (empty($field)) {
            $field = 'id,user_id,module_id,request_method,request_ip,request_region,request_isp,response_code,response_msg,create_time';
        }

        $where[] = ['is_delete', '=', 0];

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('log')
            ->where($where)
            ->count('id');

        $list = Db::name('log')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['username'] = '';
            $list[$k]['nickname'] = '';
            $admin_user = AdminUserService::info($v['admin_user_id']);
            if ($admin_user) {
                $list[$k]['username'] = $admin_user['username'];
                $list[$k]['nickname'] = $admin_user['nickname'];
            }

            $list[$k]['menu_name'] = '';
            $list[$k]['menu_url']  = '';
            $admin_menu = AdminMenuService::info($v['admin_menu_id']);
            if ($admin_menu) {
                $list[$k]['menu_name'] = $admin_menu['menu_name'];
                $list[$k]['menu_url']  = $admin_menu['menu_url'];
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
            $log = Db::name('log')
                ->where('id', $id)
                ->find();

            if (empty($log)) {
                exception('日志管理不存在：' . $id);
            }

            if ($log['request_param']) {
                $log['request_param'] = unserialize($log['request_param']);
            }

            $log['username'] = '';
            $log['nickname'] = '';
            $admin_user = AdminUserService::info($log['admin_user_id']);
            if ($admin_user) {
                $log['username'] = $admin_user['username'];
                $log['nickname'] = $admin_user['nickname'];
            }

            $log['menu_name'] = '';
            $log['menu_url']  = '';
            $admin_menu = AdminMenuService::info($log['admin_menu_id']);
            if ($admin_menu) {
                $log['menu_name'] = $admin_menu['menu_name'];
                $log['menu_url']  = $admin_menu['menu_url'];
            }

            AdminUserLogCache::set($id, $log);
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

        //$param['module_id']        = $module['id'];
        unset($param['id'] );
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

        Db::name('log')->strict(false)
            ->insert($param);
    }

    /**
     * 日志管理修改
     *
     * @param array $param 日志管理
     * 
     * @return array
     */
    public static function edit($param = [])
    {
        $id = $param['id'];

        unset($param['id']);

        $param['request_param'] = serialize($param['request_param']);
        $param['update_time']   = datetime();

        $res = Db::name('log')
            ->where('id', $id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['id'] = $id;

        AdminUserLogCache::del($id);

        return $param;
    }

    /**
     * 日志管理删除
     *
     * @param integer $id 日志管理id
     * 
     * @return array
     */
    public static function dele($id)
    {
        $update['is_delete']   = 1;
        $update['delete_time'] = datetime();

        $res = Db::name('log')
            ->where('id', $id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        $update['id'] = $id;

        AdminUserLogCache::del($id);

        return $update;
    }

    /**
     * 日志管理清除
     *
     * @param integer $param 清除条件
     * 
     * @return array
     */
    public static function clear($param)
    {
        $admin_user_id = $param['admin_user_id'];
        $admin_menu_id = $param['admin_menu_id'];
        $clear_date    = $param['clear_date'];

        $where = [];
        
        if ($admin_user_id) {
            $where[] = ['admin_user_id', '=', $admin_user_id];
        }
        if ($admin_menu_id) {
            $where[] = ['admin_menu_id', '=', $admin_menu_id];
        }
        if ($clear_date) {
            $sta_date = $clear_date[0];
            $end_date = $clear_date[1];

            $where[] = ['create_time', '>=', $sta_date . ' 00:00:00'];
            $where[] = ['create_time', '<=', $end_date . ' 23:59:59'];
        }

        $res = Db::name('log')
            ->where($where)
            ->delete(true);

        $data['count'] = $res;

        return $data;
    }

    /**
     * 日志管理数量统计
     *
     * @param string $date 日期
     *
     * @return integer
     */
    public static function statNum($date = 'total')
    {
        $key  = $date;
        $data = AdminUserLogCache::get($key);

        if (empty($data)) {
            $where[] = ['is_delete', '=', 0];

            if ($date == 'total') {
                $where[] = ['id', '>', 0];
            } else {
                if ($date == 'yesterday') {
                    $yesterday = DatetimeUtils::yesterday();
                    list($sta_time, $end_time) = DatetimeUtils::datetime($yesterday);
                } elseif ($date == 'thisweek') {
                    list($start, $end) = DatetimeUtils::thisWeek();
                    $sta_time = DatetimeUtils::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = DatetimeUtils::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'lastweek') {
                    list($start, $end) = DatetimeUtils::lastWeek();
                    $sta_time = DatetimeUtils::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = DatetimeUtils::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'thismonth') {
                    list($start, $end) = DatetimeUtils::thisMonth();
                    $sta_time = DatetimeUtils::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = DatetimeUtils::datetime($end);
                    $end_time = $end_time[1];
                } elseif ($date == 'lastmonth') {
                    list($start, $end) = DatetimeUtils::lastMonth();
                    $sta_time = DatetimeUtils::datetime($start);
                    $sta_time = $sta_time[0];
                    $end_time = DatetimeUtils::datetime($end);
                    $end_time = $end_time[1];
                } else {
                    $today = DatetimeUtils::today();
                    list($sta_time, $end_time) = DatetimeUtils::datetime($today);
                }

                $where[] = ['create_time', '>=', $sta_time];
                $where[] = ['create_time', '<=', $end_time];
            }

            $data = Db::name('log')
                ->field('id')
                ->where($where)
                ->count('id');

            LogCache::set($key, $data);
        }

        return $data;
    }

    /**
     * 日志管理日期统计
     *
     * @param array $date 日期范围
     *
     * @return array
     */
    public static function statDate($date = [])
    {
        if (empty($date)) {
            $date[0] = DatetimeUtils::daysAgo(29);
            $date[1] = DatetimeUtils::today();
        }

        $sta_date = $date[0];
        $end_date = $date[1];

        $key  = 'date:' . $sta_date . '-' . $end_date;
        $data = AdminUserLogCache::get($key);

        if (empty($data)) {
            $sta_time = DatetimeUtils::dateStartTime($sta_date);
            $end_time = DatetimeUtils::dateEndTime($end_date);

            $field   = "count(create_time) as num, date_format(create_time,'%Y-%m-%d') as date";
            $where[] = ['create_time', '>=', $sta_time];
            $where[] = ['create_time', '<=', $end_time];
            $group   = "date_format(create_time,'%Y-%m-%d')";

            $log = Db::name('log')
                ->field($field)
                ->where($where)
                ->group($group)
                ->select();

            $x_data = DatetimeUtils::betweenDates($sta_date, $end_date);
            $y_data = [];

            foreach ($x_data as $k => $v) {
                $y_data[$k] = 0;
                foreach ($log as $ka => $va) {
                    if ($v == $va['date']) {
                        $y_data[$k] = $va['num'];
                    }
                }
            }

            $data['x_data'] = $x_data;
            $data['y_data'] = $y_data;
            $data['date']   = $date;

            AdminUserLogCache::set($key, $data);
        }

        return $data;
    }

    /**
     * 日志管理地区统计
     *
     * @param integer $date   日期范围
     * @param string  $region 地区类型
     * @param integer $top    top排行
     *   
     * @return array
     */
    public static function statRegion($date = [], $region = 'city', $top = 20)
    {
        if (empty($date)) {
            $date[0] = DatetimeUtils::daysAgo(29);
            $date[1] = DatetimeUtils::today();
        }

        $sta_date = $date[0];
        $end_date = $date[1];

        $key  = ':' . $sta_date . '-' . $end_date . ':top:' . $top;

        if ($region == 'country') {
            $group = 'request_country';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } elseif ($region == 'province') {
            $group = 'request_province';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } elseif ($region == 'isp') {
            $group = 'request_isp';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        } else {
            $group = 'request_city';
            $key   = $group . $key;
            $field = $group . ' as x_data';
            $where[] = [$group, '<>', ''];
        }

        $data = AdminUserLogCache::get($key);

        if (empty($data)) {
            $sta_time = DatetimeUtils::dateStartTime($date[0]);
            $end_time = DatetimeUtils::dateEndTime($date[1]);

            $where[] = ['is_delete', '=', 0];
            $where[] = ['create_time', '>=', $sta_time];
            $where[] = ['create_time', '<=', $end_time];

            $log = Db::name('log')
                ->field($field . ', COUNT(id) as y_data')
                ->where($where)
                ->group($group)
                ->order('y_data desc')
                ->limit($top)
                ->select();

            $x_data = [];
            $y_data = [];
            $p_data = [];

            foreach ($log as $k => $v) {
                $x_data[] = $v['x_data'];
                $y_data[] = $v['y_data'];
                $p_data[] = ['value' => $v['y_data'], 'name' => $v['x_data']];
            }

            $data['x_data'] = $x_data;
            $data['y_data'] = $y_data;
            $data['p_data'] = $p_data;
            $data['date']   = $date;

            AdminUserLogCache::set($key, $data);
        }

        return $data;
    }
}
