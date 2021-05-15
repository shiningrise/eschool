<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class DepartmentService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,QyId,remark,active,sort,name,code';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $count = Db::name('department')
            ->where($where)
            ->count('id');

        $list = Db::name('department')
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
        $department = Db::name('department')
            ->where($where)
            ->find();
        return $department;
    }

    public static function add($param)
    {
        $id = Db::name('department')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('department')
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
        Db::name('department')->delete($id);
        return $id;
    }

}
