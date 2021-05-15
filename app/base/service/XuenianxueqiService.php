<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class XuenianxueqiService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,name,beizhu,startdate,ishidden,iscurrent,xueqi,xuenian,bianhao';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('xuenianxueqi')
            ->where($where)
            ->count('id');

        $list = Db::name('xuenianxueqi')
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
        $xuenianxueqi = Db::name('xuenianxueqi')
            ->where($where)
            ->find();
        return $xuenianxueqi;
    }

    public static function add($param)
    {
        $id = Db::name('xuenianxueqi')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('xuenianxueqi')
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
        Db::name('xuenianxueqi')->delete($id);
        return $id;
    }

}
