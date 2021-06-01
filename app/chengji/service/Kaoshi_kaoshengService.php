<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;

class Kaoshi_kaoshengService{
	
	//根据考试ID与试场号列出考生
	public static function listByKaohsiIdAndShingchangNum($kaoshi_id,$shichang_num){
		$field = 's.xh,s.name,k.*';
		$order = ['k.zhunkaozhenghao' => 'asc'];
		$where = [];
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		$where[] = ['k.shichangnum','=',$shichang_num];
		$data = Db::name('kaoshi_kaosheng')
			->alias('k')
			->join('student s','s.id = k.student_id')
		    ->field($field)
		    ->where($where)
		    ->order($order)
		    ->select()
		    ->toArray();
		return $data;
	}
	
	//根据考试与班级按学号列出考生
	public static function listByKaohsiIdAndBanjiId($kaoshi_id,$banji_id){
		$field = 's.xh,s.name,k.*';
		$order = ['s.xh' => 'asc'];
		$where = [];
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		$where[] = ['k.banji_id','=',$banji_id];
		$data = Db::name('kaoshi_kaosheng')
			->alias('k')
			->join('student s','s.id = k.student_id')
		    ->field($field)
		    ->where($where)
		    ->order($order)
		    ->select()
		    ->toArray();
		return $data;
	}
	//列出参与考试的班级
	public static function listKaoshiBanjis($kaoshi_id){
		$field = 'b.id,b.name,b.bianhao';
		$order = ['b.bianhao' => 'asc'];
		$where = [];
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		$data = Db::name('kaoshi_kaosheng')
			->alias('k')
			->join('banji b','b.id = k.banji_id')
			->distinct(true)
		    ->field($field)
		    ->where($where)
		    ->order($order)
		    ->select()
		    ->toArray();
		return $data;
	}

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
            $field = 'k.id,k.student_id,s.xh student_xh,s.name student_name,k.banji_id,b.name banji_name,kaoshi_id,mc2,mc1,zongfen,active,zhunkaozhenghao,zuoweihao,shichangnum,xuhao';
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
			$data = ['kaoshi_id' => $kaoshi_id, 'banji_id' => $student['banji_id'],'student_id'=>$student['id'],'active'=>1];
			Db::name('kaoshi_kaosheng')->insert($data);
		}
	    return 'ok';
	}
}
