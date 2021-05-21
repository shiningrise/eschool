<?php
namespace app\pingjiao\service;
use think\facade\Db;
use think\facade\Log;
use think\facade\Filesystem;
use app\base\service\ShoukebiaoService;
use app\base\service\StudentService;
class Pingjiao_contentService{
	public static function save($data)
	{
		$user_id = user_id();
		$student = StudentService::getByUsrId($user_id);
		foreach($data as $item)
		{
			$item['student_id'] = $student['id'];
			self::add($item);
		}
		$rtn = 'ok';
	    return $rtn;
	}
	
	public static function getPingjiaoTable()
	{
		$order = ['id' => 'desc'];
		$where[]=['active','=',1];
		$pingjiao = Db::name('pingjiao')
		    ->where($where)
		    ->order($order)
		    ->find();
		if(!$pingjiao){
			exception('没有评教任务');
		}
		$order = ['dm' => 'asc'];
		$zhibiaoWhere[]=['pingjiao_id','=',$pingjiao['id']];
		$zhibiaos = Db::name('pingjiao_zhibiao')
		    ->where($zhibiaoWhere)
		    ->order($order)
		    ->select()
			->toArray();
		$dengdis = Db::name('pingjiao_dengdi')
		    ->where($zhibiaoWhere)
		    ->order($order)
		    ->select()
			->toArray();
		$data['pingjiao']=$pingjiao;
		$data['zhibiaos']=$zhibiaos;
		$data['dengdis']=$dengdis;
	    return $data;
	}
	
	public static function listShoukebiaoByUserId($user_id)
	{
		$data = ShoukebiaoService::listByStudentUserId($user_id);
		$order = ['id' => 'desc'];
		$where[]=['active','=',1];
		$pingjiao = Db::name('pingjiao')
		    ->where($where)
		    ->order($order)
		    ->find();
		if(!$pingjiao){
			exception('没有评教任务');
		}
		$user_id = user_id();
		$student = StudentService::getByUsrId($user_id);
		foreach($data as &$item){
			$where1 = [];
			$where1[]=['banji_id','=',$item['banji_id']];
			$where1[]=['xueke_id','=',$item['xueke_id']];
			$where1[]=['teacher_id','=',$item['teacher_id']];
			$where1[]=['student_id','=',$student['id']];
			$where1[]=['pingjiao_id','=',$pingjiao['id']];
			$flag = Db::name('pingjiao_content')->where($where1)->count('id');
			
			if($flag>0){
				$item['btnTitle']='已评';
				$item['disabled']=true;
			}else{
				$item['btnTitle']='评价';
				$item['disabled']=false;
			}
		}
		
		Log::info($data);
	    return $data;
	}
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
