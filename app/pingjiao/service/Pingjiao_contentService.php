<?php
namespace app\pingjiao\service;
use think\facade\Db;
use think\facade\Filesystem;

class Pingjiao_contentService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,student_id,teacher_id,xueke_id,banji_id,pingjiao_id,defen,quanzhong,fenshu,answer,zhibiao_dm';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('pingjiao_content')
            ->where($where)
            ->count('id');

        $list = Db::name('pingjiao_content')
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
        $pingjiao_content = Db::name('pingjiao_content')
            ->where($where)
            ->find();
        return $pingjiao_content;
    }

    public static function add($param)
    {
        $id = Db::name('pingjiao_content')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('pingjiao_content')
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
        Db::name('pingjiao_content')->delete($id);
        return $id;
    }

}
