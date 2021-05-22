<?php
/*
 * @Description  : 用户管理
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\service;

use think\facade\Db;
use think\facade\Log;
use think\facade\Config;
use think\facade\Filesystem;
use app\admin\cache\UserCache;
use app\admin\model\UserModel;
use app\admin\model\RoleModel;

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
        //$res = Db::name('user')->where('id', $id)->find();
        //roleids
        $user = UserModel::find($id);
        $dbroleids = array_column($user->roles()->select()->toArray(), 'id');
        $user['roleids']=$dbroleids;
        return $user;
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
        $roleids = $param['roleids'];
        unset($param['roleids']);
        //$id = Db::name('user')->insertGetId($param);
        $user = new UserModel;
        $user->save($param);
        $id=$user->id;
        $param['id'] = $id;
        foreach($roleids as $roleid)
        {
            if($roleid>0)  $user->roles()->save($roleid);
        }

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
        $roleids = $param['roleids'];
        unset($param['roleids']);
        $res = Db::name('user')
            ->where('id', $id)
            ->update($param);

        $user = UserModel::find($id);
        $dbroles = $user->roles;
        foreach ($dbroles as $dbrole) 
        {
            if (!in_array($dbrole->id, $roleids))
            {
                $user->roles()->detach($dbrole->id);
            }
        }
        $dbroleids = array_column($user->roles()->select()->toArray(),'id');//
        foreach($roleids as $roleid)
        {
             if (!in_array($roleid, $dbroleids))
             {
                if($roleid>0)  $user->roles()->save($roleid);
             }
        }

        // if (empty($res)) {
        //     exception();
        // }

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
		$super_ids = Config::get('admin.super_ids');
		if(in_array($id,$super_ids)){
			exception('超级管理员不能删除');
		}
        $user = UserModel::find($id);
        $dbroles = $user->roles;
        foreach ($dbroles as $dbrole) 
        {
            $user->roles()->detach($dbrole->id);
        }
        $user->delete();
        return $id;
    }

        /**
     * 管理员重置密码
     *
     * @param array $param 管理员信息
     * 
     * @return array
     */
    public static function pwd($param)
    {
        $id = $param['id'];

        $update['password']    = md5($param['password']);

        $res = Db::name('user')
            ->where('id', $id)
            ->update($update);

        // if (empty($res)) {
        //     exception();
        // }

        $update['id'] = $id;
        $update['password']       = $res;

        UserCache::upd($id);

        return $update;
    }

        /**
     * 用户更换头像
     *
     * @param array $param 头像信息
     * 
     * @return array
     */
    public static function avatar($param)
    {
        $id = $param['id'];
        $avatar        = $param['avatar'];

        $avatar_name = Filesystem::disk('public')
            ->putFile('user', $avatar, function () use ($id) {
                return $id . '/' . $id . '_avatar';
            });

        $update['avatar']      = 'storage/' . $avatar_name . '?t=' . date('YmdHis');

        $res = Db::name('user')
            ->where('id', $id)
            ->update($update);

        if (empty($res)) {
            exception();
        }
        $user = UserService::info($id);

        $data['id'] = $user['id'];
        $data['avatar']        = file_url($user['avatar']);

        return $data;
    }
	
	public static function multiDelete($ids)
	{
		$super_ids = Config::get('admin.super_ids');
	    foreach($ids as $id)
	    {
			if(in_array($id,$super_ids)){
				exception('超级管理员不能删除');
			}
	        Db::name('user')->delete($id);
	    }
	    return $ids;
	}
}