<?php
/*
 * @Description  : 用户个人中心
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-10-12
 * @LastEditTime : 2021-04-12
 */

namespace app\admin\service;

use think\facade\Db;
use app\admin\cache\UserCache;

class UserCenterService
{
    /**
     * 我的信息
     *
     * @param integer $id 用户id
     * 
     * @return array
     */
    public static function info($id)
    {
        $user = UserService::info($id);

        $data['id'] = $user['id'];
        //$data['avatar']        = $admin_user['avatar'];
        $data['username']       = $user['username'];
        $data['fullname']       = $user['fullname'];
        $data['roles']          = ModuleService::getModuleUrlByUserId($id);
        $data['menus']          = MenuService::getByUserId($id);
        // $data['nickname']      = $admin_user['nickname'];
        // $data['email']         = $admin_user['email'];
        // $data['phone']         = $admin_user['phone'];
        // $data['create_time']   = $admin_user['create_time'];
        // $data['update_time']   = $admin_user['update_time'];
        // $data['login_time']    = $admin_user['login_time'];
        // $data['logout_time']   = $admin_user['logout_time'];
        // $data['is_delete']     = $admin_user['is_delete'];
        // $data['roles']         = $admin_user['roles'];

        return $data;
    }

    /**
     * 修改信息
     *
     * @param array $param 用户信息
     * 
     * @return array
     */
    public static function edit($param)
    {
        $id = $param['id'];
        
        unset($param['id']);
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
     * 修改密码
     *
     * @param array $param 用户密码
     * 
     * @return array
     */
    public static function pwd($param)
    {
        $id = $param['id'];
        $password_old  = $param['password_old'];
        $password_new  = $param['password_new'];

        $user = UserService::info($id);

        if (md5($password_old) != $user['password']) {
            exception('旧密码错误');
        }

        $update['password']    = md5($password_new);

        $res = Db::name('user')
            ->where('id', $id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        $update['id'] = $id;
        $update['password']= $res;

        return $update;
    }

    /**
     * 更换头像
     *
     * @param array $param 头像信息
     * 
     * @return array
     */
    public static function avatar($param)
    {
        $data = UserService::avatar($param);

        return $data;
    }

    /**
     * 我的日志
     *
     * @param array   $where 条件
     * @param integer $page  页数
     * @param integer $limit 数量
     * @param array   $order 排序
     * @param string  $field 字段
     * 
     * @return array 
     */
    public static function log($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        $data = AdminUserLogService::list($where, $page, $limit, $order, $field);

        return $data;
    }
}
