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
use app\admin\model\PermissionModel;
use app\admin\model\RoleModel;
use app\admin\model\UserModel;
class PermissionService{

    /**
     * 权限列表
     *
     * @param array   $where 条件
     * @param integer $page  页数
     * @param integer $limit 数量
     * @param array   $order 排序
     * @param string  $field 字段
     * 
     * @return array 
     */
    public static function list($type = 'tree')
    {
        if (empty($field)) {
            $field = 'id,code,name,parent_id,sort,remark';
        }

        $order = ['sort'=>'desc','id' => 'asc'];
        $list = Db::name('permission')
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
        $tree = self::toTree($list, 0);
        $permission['tree'] = $tree;
        $permission['list'] = $list;
        if ($type == 'list') {
            $data['count'] = count($permission['list']);
            $data['list']  = $permission['list'];
        } else {
            $data['count'] = count($permission['tree']);
            $data['list']  = $permission['tree'];
        }
        return $data;
    }
    public static function toTree($list, $parent_id)
    {
        $tree = [];

        foreach ($list as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $v['children'] = self::toTree($list, $v['id']);
                $tree[] = $v;
            }
        }

        return $tree;
    }
    /**
     * 权限信息
     *
     * @param integer $id 权限id
     * 
     * @return array
     */
    public static function info($id)
    {
        $data = Db::name('permission')
        ->where('id', $id)
        ->find();
        return $data;
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
        $id = Db::name('permission')
            ->insertGetId($param);

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
        $res = Db::name('permission')
            ->where('id', $id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

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
        Db::table('permission')->delete($id);
        return $id;
    }

    public static function getPermissionCodeByRoleId($roleid)
    {
        $role = RoleModel::find($roleid);
        $permission_codes = array_column($role->permissions()->select()->toArray(),'code');
        return $permission_codes;
    }
    public static function getPermissionCodeByUserId($userid)
    {
        $permission_codes_all = [];
        $user = UserModel::find($userid);
        $roles = $user->roles;
        foreach($roles as $role)
        {
            $permission_codes = self::getPermissionCodeByRoleId($role->id);
            $permission_codes_all=array_merge($permission_codes_all,$permission_codes);
        }
        return formatArray($permission_codes_all);
    }
}