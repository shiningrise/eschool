<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;

class Kaoshi_shichangService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,kaoshi_id,renshu,address,num';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('kaoshi_shichang')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi_shichang')
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
        $kaoshi_shichang = Db::name('kaoshi_shichang')
            ->where($where)
            ->find();
        return $kaoshi_shichang;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi_shichang')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi_shichang')
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
        Db::name('kaoshi_shichang')->delete($id);
        return $id;
    }

}
