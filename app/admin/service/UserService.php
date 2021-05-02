<?php
/*
 * @Description  : 用户管理
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\Filesystem;
use app\admin\cache\UserCache;

class UserService{

    /**
     * 新闻列表
     *
     * @param array   $where 条件
     * @param integer $page  页数
     * @param integer $limit 数量
     * @param array   $order 排序
     * @param string  $field 字段
     * 
     * @return array 
     */
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,username,fullname,is_approved,is_lockedout,last_lockout_date';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $where[] = ['is_delete', '=', 0];

        $count = Db::name('user')
            ->where($where)
            ->count('id');

        $list = Db::name('user')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

        $pages = ceil($count / $limit);

        $data['count'] = $count;
        $data['pages'] = $pages;
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['list']  = $list;

        return $data;
    }

    
    /**
     * 用户信息
     *
     * @param integer $id 用户id
     * 
     * @return array
     */
    public static function info($id)
    {
        $res = Db::name('user')
        ->where('id', $id)
        ->find();
        return $res;
    }

    /**
     * 管理员信息
     *
     * @param integer $user_id 管理员id
     * 
     * @return array
     */
    public static function UserInfo($user_id)
    {
        $user = UserCache::get($user_id);

        if (empty($user)) {
            $user = Db::name('user')
                ->where('id', $user_id)
                ->find();

            if (empty($user)) {
                exception('用户不存在：' . $user_id);
            }
            $user['token']    = TokenService::create($user);
            UserCache::set($user_id, $user);
        }

        return $user;
    }

    /**
     * 用户添加
     *
     * @param array $param 用户信息
     * 
     * @return array
     */
    public static function add($param)
    {
        $id = Db::name('user')
            ->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    /**
     * 用户修改
     *
     * @param array $param 用户信息
     * 
     * @return array
     */
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('user')
            ->where('id', $id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    /**
     * 用户删除
     *
     * @param integer $id 用户id
     * 
     * @return array
     */
    public static function del($id)
    {
        Db::table('user')->delete($id);
        return $id;
    }
}