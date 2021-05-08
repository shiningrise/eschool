<?php
/*
 * @Description  : 用户管理
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\service;
use app\admin\cache\ModuleCache;
use think\facade\Db;
use think\facade\Filesystem;

class ModuleService{

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
            $field = 'id,name,url,parent_id,permission_code,sort,remark';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $where[] = [];

        $count = Db::name('module')
        //    ->where($where)
            ->count('id');

        $list = Db::name('module')
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
     * 权限信息
     *
     * @param integer $id 权限id
     * 
     * @return array
     */
    public static function info($id='')
    {
        if (empty($id)) {
            $id = request_pathinfo();
        }
        if (is_numeric($id)) {
            $where[] = ['id', '=',  $id];
        } else {
            $where[] = ['url', '=',  $id];
        }

        $module = Db::name('module')
            ->where($where)
            ->find();

        if (empty($module)) {
            //exception('菜单不存在：' . $admin_menu_id);
            $module['url']=request_pathinfo();
            Db::name('module')->insert($module);
        }
        return $module;
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
        $id = Db::name('module')
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
        $res = Db::name('module')
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
        Db::name('module')->delete($id);
        return $id;
    }
}