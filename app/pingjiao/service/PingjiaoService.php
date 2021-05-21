<?php
namespace app\pingjiao\service;
use think\facade\Db;
use think\facade\Filesystem;

class PingjiaoService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,beizhu,isdeleted,active,enddate,startdate,name';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('pingjiao')
            ->where($where)
            ->count('id');

        $list = Db::name('pingjiao')
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
        $pingjiao = Db::name('pingjiao')
            ->where($where)
            ->find();
        return $pingjiao;
    }

    public static function add($param)
    {
        $id = Db::name('pingjiao')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('pingjiao')
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
        Db::name('pingjiao')->delete($id);
        return $id;
    }

	public static function copy($id)
	{
	    $pingjiao = Db::name('pingjiao')->where('id',$id)->find();
		$oldPingjiaoId = $pingjiao['id'];
		unset($pingjiao['id']);
		$pingjiao['name'] = $pingjiao['name'] . '复制';
		$param = self::add($pingjiao);
		$zhibiaos = Db::name('pingjiao_zhibiao')->where('pingjiao_id',$oldPingjiaoId)->select()->toArray();
		foreach($zhibiaos as $zhibiao){
			unset($zhibiao['id']);
			$zhibiao['pingjiao_id']=$param['id'];
			Db::name('pingjiao_zhibiao')->insert($zhibiao);
		}
		$dengdis = Db::name('pingjiao_dengdi')->where('pingjiao_id',$oldPingjiaoId)->select()->toArray();
		foreach($dengdis as $dengdi){
			unset($dengdi['id']);
			$dengdi['pingjiao_id']=$param['id'];
			Db::name('pingjiao_dengdi')->insert($dengdi);
		}
	    return $param ['id'];
	}
}
