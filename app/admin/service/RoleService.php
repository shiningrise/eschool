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
use app\admin\model\RoleModel;

class RoleService{

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
            $field = 'id,rolename,beizhu';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        //$where[] = [];

        $count = Db::name('role')
            ->where($where)
            ->count('id');

        $list = Db::name('role')
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
     * 角色信息
     *
     * @param integer $id 角色id
     * 
     * @return array
     */
    public static function info($id)
    {
        //$data = Db::name('role')->where('id', $id)->find();

        $role = RoleModel::find($id);

        $role['permission_ids'] = array_column($role->permissions()->select()->toArray(),'id');

        return $role;
    }

    /**
     * 角色添加
     *
     * @param array $param 角色信息
     * 
     * @return array
     */
    public static function add($param)
    {
    //    $param['rolename']   = datetime();
        $permission_ids = $param['permission_ids'];
        unset($param['permission_ids']);
        $id = Db::name('role')->insertGetId($param);
        $role = RoleModel::find($id);
        
        foreach($permission_ids as $permission_id)
        {
            $role->permissions()->save($permission_id);
        }

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    /**
     * 角色修改
     *
     * @param array $param 角色信息
     * 
     * @return array
     */
    public static function edit($param)
    {
        $id = $param['id'];
        //$res = Db::name('role')->where('id', $id)->update($param);
        $role = RoleModel::find($id);
        $role->rolename = $param['rolename'];
        $role->beizhu   = $param['beizhu'];
        $permission_ids = $param['permission_ids'];

        $permissions = $role->permissions;
        foreach ($permissions as $permission) 
        {
            if (!in_array($permission->id, $permission_ids))
            {
                $role->permissions()->detach($permission->id);
            }
        }
        $dbpermission_ids = array_column($role->permissions()->select()->toArray(),'id');//
        foreach($permission_ids as $permission_id)
        {
             if (!in_array($permission_id, $dbpermission_ids))
             {
                $role->permissions()->save($permission_id);
             }
        }
        // if (empty($res)) {
        //     exception();
        // }

        $param['id'] = $id;

        return $param;
    }

    /**
     * 角色删除
     *
     * @param integer $id 角色id
     * 
     * @return array
     */
    public static function del($id)
    {
        //Db::table('role')->delete($id);
        $role = RoleModel::find($id);
        $users = $role->users;
        foreach ($users as $user) 
        {
            $role->users()->detach($user->id);
        }
        $permissions = $role->permissions;
        foreach ($permissions as $permission) 
        {
            $role->permissions()->detach($permission->id);
        }
        $role->delete();
        return $id;
    }
}