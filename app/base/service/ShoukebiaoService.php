<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class ShoukebiaoService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,teacher_id,xueke_id,banji_id';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('shoukebiao')
            ->where($where)
            ->count('id');

        $list = Db::name('shoukebiao')
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
        $shoukebiao = Db::name('shoukebiao')
            ->where($where)
            ->find();
        return $shoukebiao;
    }

    public static function add($param)
    {
        $id = Db::name('shoukebiao')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('shoukebiao')
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
        Db::name('shoukebiao')->delete($id);
        return $id;
    }

}
