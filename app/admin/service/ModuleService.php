<?php
namespace app\admin\service;
use app\admin\cache\ModuleCache;
use think\facade\Db;
use think\facade\Filesystem;

class ModuleService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,name,url,permission_code,sort,remark';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $count = Db::name('module')
            ->where($where)
            ->count('id');

        $list = Db::name('module')
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

        $module = Db::name('module')->where($where)->find();

        if (empty($module)) {
            $module['url']=request_pathinfo();
            $module['id']=Db::name('module')->insertGetId($module);
        }
        return $module;
    }

    public static function add($param)
    {
        $id = Db::name('module')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
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

    public static function del($id)
    {
        Db::name('module')->delete($id);
        return $id;
    }

    /*
     * 获取用户可访问功能模块
     */
    public static function getModuleUrlByUserId($userid)
    {
        $permission_codes = PermissionService::getPermissionCodeByUserId($userid);
        $list = Db::name('module')->select()->toArray();
        $data = [];
        foreach($list as $module)
        {
            if(in_array($module['permission_code'], $permission_codes)) 
            {
                $data[]=$module['url'];
            }
        }
        return $data;
    }
}