<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;

class KaoshiService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,name,status,beizhu,pernum,leibie,xueqi,xuenian';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('kaoshi')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi')
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
        $kaoshi = Db::name('kaoshi')
            ->where($where)
            ->find();
        return $kaoshi;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi')
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
        Db::name('kaoshi')->delete($id);
        return $id;
    }

	public static function copy($id)
    {
        $kaoshi = Db::name('kaoshi')->find($id);
		$oldId = $kaoshi['id'];
		unset($kaoshi['id']);
		$kaoshi['name'] .= '复制';
		$id = Db::name('kaoshi')->insertGetid($kaoshi);
		$xuekes = Db::name('kaoshi_xueke')->where('kaoshi_id',$oldId)->select()->toArray();
		foreach($xuekes as $xueke){
			unset($xueke['id']);
			$xueke['kaoshi_id'] = $id;
			Db::name('kaoshi_xueke')->insert($xueke);
		}
		$shichangs = Db::name('kaoshi_shichang')->where('kaoshi_id',$oldId)->select()->toArray();
		foreach($shichangs as $shichang){
			unset($shichang['id']);
			$shichang['kaoshi_id'] = $id;
			Db::name('kaoshi_shichang')->insert($shichang);
		}
        return $id;
    }
}
