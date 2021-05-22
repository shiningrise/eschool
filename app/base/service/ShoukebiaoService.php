<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class ShoukebiaoService{

	//根据学生用户ID获取授课表用于学生评教
	public static function listByStudentUserId($user_id)
    {
		$user = Db::name('user')->where('id',$user_id)->find();
		$where[] = ['stu.xh', '=',$user['username']];
		$field = 's.teacher_id,s.xueke_id,s.banji_id,t.name teacher,b.name banji,x.name xueke';
        $data=Db::name('shoukebiao')
        ->alias('s')
        ->join('banji b','s.banji_id = b.id')
		->join('student stu','stu.banji_id = b.id')
		->join('xueke x','s.xueke_id = x.id')
		->join('teacher t','s.teacher_id = t.id')
        ->field($field)
        ->where($where)
        ->select()
		->toArray();
        return $data;
    }
	
	//获取现用授课表全部数据
    public static function getTable()
    {
        $data['banjis']=BanjiService::getUngraduatedBanjis();
        $data['xuekes']=XuekeService::getActiveXuekes();
        $data['shoukebiaos']=self::getActiveList();
        $data['teachers']=TeacherService::getList();
        return $data;
    }
    //获取现用授课表主表内容
    public static function getActiveList()
    {
        $where[] = ['b.is_graduated', '=',0];
        $field = 's.id,teacher_id,xueke_id,banji_id';
        $data = Db::name('shoukebiao')
            ->alias('s')
            ->join('banji b','s.banji_id = b.id')
            ->join('xueke x','s.xueke_id = x.id')
            ->field($field)
            ->where($where)
            ->select()
			->toArray();
        return $data;
    }

    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,teacher_id,xueke_id,banji_id';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('shoukebiao')
            ->where($where)
            ->count('id');

        $list = Db::name('shoukebiao')
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
        $shoukebiao = Db::name('shoukebiao')
            ->where($where)
            ->find();
        return $shoukebiao;
    }

    public static function add($param)
    {
        $id = Db::name('shoukebiao')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('shoukebiao')
            ->where('id', $id)
            ->update($param);

        // if (empty($res)) {
        //     exception();
        // }

        $param['id'] = $id;

        return $param;
    }

    public static function del($id)
    {
        Db::name('shoukebiao')->delete($id);
        return $id;
    }

}
