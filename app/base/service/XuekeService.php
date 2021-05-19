<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class XuekeService{

    public static function getActiveXuekes()
    {
        $where[] = ['active', '=', 1];
        $order = ['sort' => 'asc'];
        $xuekeList = Db::name('xueke')->where($where)->order($order)->select()->toArray();
        return $xuekeList;
    }

    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,remark,ismain,active,code,shortname,name,sort';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $count = Db::name('xueke')
            ->where($where)
            ->count('id');

        $list = Db::name('xueke')
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
        $where[] = ['id', '=',  $id];
        $xueke = Db::name('xueke')
            ->where($where)
            ->find();
        return $xueke;
    }

    public static function add($param)
    {
        $id = Db::name('xueke')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('xueke')
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
        Db::name('xueke')->delete($id);
        return $id;
    }

}
