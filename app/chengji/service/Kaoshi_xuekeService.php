<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;
use app\base\service\XuekeService;

class Kaoshi_xuekeService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,xueke_id,kaoshi_id,havejiduka,zongfen,quanzhong,shijian';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('kaoshi_xueke')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi_xueke')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();
		foreach($list as &$item){
			$xueke = XuekeService::getById($item['xueke_id']);
			$item['xueke_name']=$xueke['name'];
		}

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
        $kaoshi_xueke = Db::name('kaoshi_xueke')
            ->where($where)
            ->find();
        return $kaoshi_xueke;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi_xueke')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi_xueke')
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
        Db::name('kaoshi_xueke')->delete($id);
        return $id;
    }

}
