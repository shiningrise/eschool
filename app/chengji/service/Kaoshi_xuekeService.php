<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;
use app\base\service\XuekeService;

class Kaoshi_xuekeService{
	public static function getByKaoshiId($kaoshi_id){
		$field = 'x.id,xueke_id,xk.name xueke_name,kaoshi_id,havejiduka,zongfen,quanzhong,shijian';
		$order = ['xk.code' => 'asc'];
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		$list = Db::name('kaoshi_xueke')
			->alias('x')
			->join('xueke xk','xk.id = x.xueke_id')
		    ->field($field)
		    ->where($where)
		    ->order($order)
		    ->select()
		    ->toArray();	
		return $list;
	}
    public static function list($where = [], $page = 1, $limit = 100,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'x.id,xueke_id,xk.name xueke_name,kaoshi_id,havejiduka,zongfen,quanzhong,shijian';
        }

        if (empty($order)) {
            $order = ['xk.code' => 'asc'];
        }

        $count = Db::name('kaoshi_xueke')
			->alias('x')
			->join('xueke xk','xk.id = x.xueke_id')
            ->where($where)
            ->count('x.id');

        $list = Db::name('kaoshi_xueke')
			->alias('x')
			->join('xueke xk','xk.id = x.xueke_id')
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
