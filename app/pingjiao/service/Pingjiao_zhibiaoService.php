<?php
namespace app\pingjiao\service;
use think\facade\Db;
use think\facade\Filesystem;

class Pingjiao_zhibiaoService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,beizhu,pingjiao_id,is_show,fenshu,name,dm';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('pingjiao_zhibiao')
            ->where($where)
            ->count('id');

        $list = Db::name('pingjiao_zhibiao')
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
        $pingjiao_zhibiao = Db::name('pingjiao_zhibiao')
            ->where($where)
            ->find();
        return $pingjiao_zhibiao;
    }

    public static function add($param)
    {
        $id = Db::name('pingjiao_zhibiao')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('pingjiao_zhibiao')
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
        Db::name('pingjiao_zhibiao')->delete($id);
        return $id;
    }

}
