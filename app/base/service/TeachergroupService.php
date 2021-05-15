<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class TeachergroupService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,remark,sort,name,code';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $count = Db::name('teachergroup')
            ->where($where)
            ->count('id');

        $list = Db::name('teachergroup')
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
        $teachergroup = Db::name('teachergroup')
            ->where($where)
            ->find();
        return $teachergroup;
    }

    public static function add($param)
    {
        $id = Db::name('teachergroup')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('teachergroup')
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
        Db::name('teachergroup')->delete($id);
        return $id;
    }

}
