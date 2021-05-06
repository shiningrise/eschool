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
     * @param integer $admin_user_id 用户id
     * 
     * @return array
     */
    public static function info($admin_user_id)
    {
        $admin_user = UserService::info($admin_user_id);

        $data['id'] = $admin_user['id'];
        //$data['avatar']        = $admin_user['avatar'];
        $data['username']      = $admin_user['username'];
        $data['fullname']      = $admin_user['fullname'];
        $data['roles']  = ["admin\/AdminIndex\/index","admin\/AdminIndex\/member","admin\/AdminLogin\/login","admin\/AdminLogin\/logout","admin\/AdminLogin\/verify","admin\/AdminMenu\/add","admin\/AdminMenu\/dele","admin\/AdminMenu\/disable","admin\/AdminMenu\/edit","admin\/AdminMenu\/info","admin\/AdminMenu\/list","admin\/AdminMenu\/role","admin\/AdminMenu\/roleRemove","admin\/AdminMenu\/unauth","admin\/AdminMenu\/user","admin\/AdminMenu\/userRemove","admin\/AdminRole\/add","admin\/AdminRole\/dele","admin\/AdminRole\/disable","admin\/AdminRole\/edit","admin\/AdminRole\/info","admin\/AdminRole\/list","admin\/AdminRole\/user","admin\/AdminRole\/userRemove","admin\/AdminSetting\/cache","admin\/AdminSetting\/info","admin\/AdminSetting\/token","admin\/AdminSetting\/verify","admin\/AdminUser\/add","admin\/AdminUser\/avatar","admin\/AdminUser\/dele","admin\/AdminUser\/disable","admin\/AdminUser\/edit","admin\/AdminUser\/info","admin\/AdminUser\/list","admin\/AdminUser\/pwd","admin\/AdminUser\/rule","admin\/AdminUser\/super","admin\/AdminUserCenter\/avatar","admin\/AdminUserCenter\/edit","admin\/AdminUserCenter\/info","admin\/AdminUserCenter\/log","admin\/AdminUserCenter\/pwd","admin\/AdminUserCenter\/setting","admin\/AdminUserLog\/clear","admin\/AdminUserLog\/dele","admin\/AdminUserLog\/info","admin\/AdminUserLog\/list","admin\/AdminUserLog\/stat","admin\/AdminUtils\/apidoc","admin\/AdminUtils\/bytetran","admin\/AdminUtils\/form","admin\/AdminUtils\/ipinfo","admin\/AdminUtils\/map","admin\/AdminUtils\/mapAmap","admin\/AdminUtils\/mapBaidu","admin\/AdminUtils\/mapBeidou","admin\/AdminUtils\/mapSogou","admin\/AdminUtils\/mapTencent","admin\/AdminUtils\/qrcode","admin\/AdminUtils\/server","admin\/AdminUtils\/strrand","admin\/AdminUtils\/strtran","admin\/AdminUtils\/timestamp","admin\/AdminUtils\/tools","admin\/Api\/add","admin\/Api\/dele","admin\/Api\/disable","admin\/Api\/edit","admin\/Api\/info","admin\/Api\/list","admin\/Api\/unauth","admin\/ApiEnv\/add","admin\/ApiEnv\/dele","admin\/ApiEnv\/edit","admin\/ApiEnv\/info","admin\/ApiEnv\/list","admin\/Member\/add","admin\/Member\/avatar","admin\/Member\/dele","admin\/Member\/disable","admin\/Member\/edit","admin\/Member\/info","admin\/Member\/list","admin\/Member\/pwd","admin\/MemberLog\/dele","admin\/MemberLog\/info","admin\/MemberLog\/list","admin\/MemberLog\/stat","admin\/News\/add","admin\/News\/dele","admin\/News\/edit","admin\/News\/info","admin\/News\/ishide","admin\/News\/ishot","admin\/News\/isrec","admin\/News\/istop","admin\/News\/list","admin\/News\/upload","admin\/Region\/add","admin\/Region\/dele","admin\/Region\/edit","admin\/Region\/info","admin\/Region\/list","admin\/Setting\/info","admin\/Setting\/token","admin\/Setting\/verify","admin\/WechatConfig\/miniEdit","admin\/WechatConfig\/miniInfo","admin\/WechatConfig\/offiEdit","admin\/WechatConfig\/offiInfo","admin\/WechatConfig\/qrcode"];
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
        $admin_user_id = $param['admin_user_id'];
        
        unset($param['admin_user_id']);

        $param['update_time'] = datetime();

        $res = Db::name('admin_user')
            ->where('admin_user_id', $admin_user_id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['admin_user_id'] = $admin_user_id;

        AdminUserCache::upd($admin_user_id);

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
        $admin_user_id = $param['admin_user_id'];
        $password_old  = $param['password_old'];
        $password_new  = $param['password_new'];

        $admin_user = AdminUserService::info($admin_user_id);

        if (md5($password_old) != $admin_user['password']) {
            exception('旧密码错误');
        }

        $update['password']    = md5($password_new);
        $update['update_time'] = datetime();

        $res = Db::name('admin_user')
            ->where('admin_user_id', $admin_user_id)
            ->update($update);

        if (empty($res)) {
            exception();
        }

        $update['admin_user_id'] = $admin_user_id;
        $update['password']      = $res;

        AdminUserCache::upd($admin_user_id);

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
        $data = AdminUserService::avatar($param);

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
