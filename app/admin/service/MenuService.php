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

class MenuService{

    /**
     * 菜单列表
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
        // if (empty($field)) {
        //     $field = 'id,menu_name,beizhu';
        // }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        //$where[] = [];

        $count = Db::name('menu')
            ->where($where)
            ->count('id');

        $list = Db::name('menu')
          //  ->field($field)
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
     * 菜单信息
     *
     * @param integer $id 菜单id
     * 
     * @return array
     */
    public static function info($id)
    {
        $data = Db::name('menu')
        ->where('id', $id)
        ->find();
        return $data;
    }

    /**
     * 菜单添加
     *
     * @param array $param 菜单信息
     * 
     * @return array
     */
    public static function add($param)
    {
        $id = Db::name('menu')
            ->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    /**
     * 菜单修改
     *
     * @param array $param 菜单信息
     * 
     * @return array
     */
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('menu')
            ->where('id', $id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    /**
     * 菜单删除
     *
     * @param integer $id 菜单id
     * 
     * @return array
     */
    public static function del($id)
    {
        Db::table('menu')->delete($id);
        return $id;
    }
}