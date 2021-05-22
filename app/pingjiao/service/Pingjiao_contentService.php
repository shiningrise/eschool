<?php
namespace app\pingjiao\service;
use think\facade\Db;
use think\facade\Log;
use think\facade\Filesystem;
use app\base\service\ShoukebiaoService;
use app\base\service\StudentService;
use app\base\service\BanjiService;
use app\base\service\XuekeService;
use app\base\service\TeacherService;
use app\base\model\BanjiModel;
use app\base\model\XuekeModel;
use app\base\model\TeacherModel;

class Pingjiao_contentService{
	//获取已评人数
	public static function getYipingRenshu($pingjiao_id,$banji_id,$xueke_id,$teacher_id)
	{
		$subQuery = Db::name('pingjiao_content')
			->field('banji_id,xueke_id,teacher_id,student_id,sum(defen) as defen')
		    ->where('pingjiao_id',$pingjiao_id)
			->group('banji_id,xueke_id,teacher_id,student_id')
		    ->buildSql();
		$where = [];
		$where[] = ['banji_id','=',$banji_id];
		$where[] = ['xueke_id','=',$xueke_id];
		$where[] = ['teacher_id','=',$teacher_id];
		$data = Db::table($subQuery . ' a')
			->field('banji_id,xueke_id,teacher_id,avg(defen) as defen,count(*) renshu')
		    ->group('banji_id,xueke_id,teacher_id')
			->where($where)
		    ->count();
		return $data;
	}
	//获取未评名单
	public static function getWeipingMingdan($pingjiao_id,$banji_id,$xueke_id,$teacher_id)
	{
		$where = [];
		$where[] = ['banji_id','=',$banji_id];
		$where[] = ['xueke_id','=',$xueke_id];
		$where[] = ['teacher_id','=',$teacher_id];
		$where[] = ['pingjiao_id','=',$pingjiao_id];
		
		$yipingStudentIds = Db::name('pingjiao_content')
			->field('student_id')
		    ->where($where)
			->group('banji_id,xueke_id,teacher_id,student_id')
		    ->select()
			->toArray();
		
		$students = Db::name('student')
			->where('banji_id',$banji_id)
		    ->select()
			->toArray();
		$data='';
		foreach($students as $student){
			if(!in_array($student['id'],$yipingStudentIds))
			{
				$data = $data . ','.$student['name'];
			}
		}
		return $data;
	}
	//列出未评学生名单
	public static function listPingjiaoState($pingjiao_id)
	{
		$data = ShoukebiaoService::getActiveList();
		
		foreach($data as &$item){
			$banji = BanjiModel::find($item['banji_id']);
			$item['banji']=$banji['name'];
			$xueke = XuekeModel::find($item['xueke_id']);
			$item['xueke']=$xueke['name'];
			$teacher = TeacherModel::find($item['teacher_id']);
			$item['teacher']=$teacher['name'];
			$item['banjiRenshu']=BanjiService::getBanjiRenshu($item['banji_id']);
			$item['yipingRenshu']=self::getYipingRenshu($pingjiao_id,$item['banji_id'],$item['xueke_id'],$item['teacher_id']);
			$item['weiPingRenshu']=$item['banjiRenshu']-$item['yipingRenshu'];
			$item['weiPingMingdan']=self::getWeipingMingdan($pingjiao_id,$item['banji_id'],$item['xueke_id'],$item['teacher_id']);
		}
	    return $data;
	}
	//评教统计教师分数
	public static function listPingjiaoTongji($pingjiao_id)
	{
		$subQuery = Db::name('pingjiao_content')
			->field('banji_id,xueke_id,teacher_id,student_id,sum(defen) as defen')
		    ->where('pingjiao_id',$pingjiao_id)
			->group('banji_id,xueke_id,teacher_id,student_id')
		    ->buildSql();
		$data = Db::table($subQuery . ' a')
			->field('banji_id,xueke_id,teacher_id,avg(defen) as defen,count(*) renshu')
		    ->group('banji_id,xueke_id,teacher_id')
		    ->select()
			->toArray();
		foreach($data as &$item){
			$banji = BanjiModel::find($item['banji_id']);
			$item['banji']=$banji['name'];
			$xueke = XuekeModel::find($item['xueke_id']);
			$item['xueke']=$xueke['name'];
			$teacher = TeacherModel::find($item['teacher_id']);
			$item['teacher']=$teacher['name'];
		}
	    return $data;
	}
	//保存学生评价老师数据
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
	//获取学生评教表格
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
	//跟据学生用户ID获取学生评教授课表
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
