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
use think\facade\Log;

class MenuService{

    /**
     * 菜单列表
     *
     * @param array   $type 条件
     * 
     * @return array 
     */
    public static function list($type = 'tree')
    {
        $order = ['sort'=>'desc','id' => 'asc'];

        $count = Db::name('menu')
            ->count('id');

        $list = Db::name('menu')
            ->order($order)
            ->select()
            ->toArray();
        $tree = self::toTree($list, 0);
        $url  = array_filter(array_column($list, 'url'));
        sort($url);
        $menu['tree'] = $tree;
        $menu['list'] = $list;
        $menu['url']  = $url;
        if ($type == 'list') {
            $data['count'] = count($menu['list']);
            $data['list']  = $menu['list'];
        } elseif ($type == 'url') {
            $data['count'] = count($menu['url']);
            $data['list']  = $menu['url'];
        } else {
            $data['count'] = count($menu['tree']);
            $data['list']  = $menu['tree'];
        }
        return $data;
    }

    /**
     * 菜单树形获取
     *
     * @param array   $admin_menu 所有菜单
     * @param integer $menu_pid   菜单父级id
     * 
     * @return array
     */
    public static function toTree($menu, $menu_pid)
    {
        $tree = [];

        foreach ($menu as $k => $v) {
            if ($v['parent_id'] == $menu_pid) {
                $v['children'] = self::toTree($menu, $v['id']);
                $tree[] = $v;
            }
        }

        return $tree;
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