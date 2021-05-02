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
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,permission_code,permission_name,parent_id,xh';
        }

        if (empty($order)) {
            $order = ['xh' => 'asc'];
        }

        $where[] = [];

        $count = Db::name('permission')
        //    ->where($where)
            ->count('id');

        $list = Db::name('permission')
            ->field($field)
         //   ->where($where)
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
}