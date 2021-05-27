<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;

class Kaoshi_kaoshengService{
	public static function listByKaoshiId($kaoshi_id)
	{
		$order = ['xuhao' => 'asc'];
	    $data = Db::name('kaoshi_kaosheng')
	        ->where('kaoshi_id',$kaoshi_id)
			->order($order)
	        ->select()
	        ->toArray();
	    return $data;
	}
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'k.id,k.student_id,s.name student_name,k.banji_id,b.name banji_name,kaoshi_id,mc2,mc1,zongfen,active,zhunkaozhenghao,zuoweihao,shichangnum,xuhao';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('kaoshi_kaosheng')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi_kaosheng')
			->alias('k')
			->join('banji b','b.id = k.banji_id')
			->join('student s','k.student_id = s.id')
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
        $kaoshi_kaosheng = Db::name('kaoshi_kaosheng')
            ->where($where)
            ->find();
        return $kaoshi_kaosheng;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi_kaosheng')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi_kaosheng')
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
        Db::name('kaoshi_kaosheng')->delete($id);
        return $id;
    }

	public static function save($kaoshi_id,$student)
	{
	    $kaosheng = Db::name('kaoshi_kaosheng')
			->where('kaoshi_id',$kaoshi_id)
			->where('student_id',$student['id'])
			->find();
		if($kaosheng == null){
			$data = ['kaoshi_id' => $kaoshi_id, 'banji_id' => $student['banji_id'],'student_id'=>$student['id']];
			Db::name('kaoshi_kaosheng')->insert($data);
		}
	    return 'ok';
	}
}
