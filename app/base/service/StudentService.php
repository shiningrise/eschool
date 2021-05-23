<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;
use app\admin\service\RoleService;
use app\admin\service\UserService;

class StudentService{
	
	public static function listByBanjiId($banji_id)
	{
		$order = ['xh' => 'asc'];
		$student = Db::name('student')
		    ->where('banji_id',$banji_id)
		    ->where('beatschool',true)
			->order($order)
		    ->select()
			->toArray();
		return $student;
	}
	public static function getByUsrId($user_id)
	{
		$user = Db::name('user')->where('id',$user_id)->find();
		
		$student = Db::name('student')
		    ->where('xh',$user['username'])
		    ->find();
		return $student;
	}
	
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,name,banji_id,beizhu2,beizhu1,beatbook,beatschool,idcardnum,sex,xh,tel,zzxh';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('student')
            ->where($where)
            ->count('id');

        $list = Db::name('student')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['banji_name'] = '';
            $banji = BanjiService::info($v['banji_id']);
            if ($banji) {
                $list[$k]['banji_name'] = $banji['name'];
            }
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
        $student = Db::name('student')
            ->where($where)
            ->find();
        $banji = BanjiService::info($student['banji_id']);
        if ($banji) {
            $student['banji_name'] = $banji['name'];
        }

        return $student;
    }

    public static function add($param)
    {
        $banji_name = $param['banji_name'];
        unset($param['banji_name']);
        $banji = BanjiService::getByName($banji_name);
        if($banji){
            $param['banji_id']=$banji['id'];
        }

        $id = Db::name('student')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $banji_name = $param['banji_name'];
        unset($param['banji_name']);
        $banji = BanjiService::getByName($banji_name);
        if($banji){
            $param['banji_id']=$banji['id'];
        }

        $id = $param['id'];
        $res = Db::name('student')
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
        Db::name('student')->delete($id);
        return $id;
    }

    public static function multiDelete($ids)
    {
        foreach($ids as $id)
        {
            Db::name('student')->delete($id);
        }
        return $ids;
    }

    public static function init($ids)
    {
        foreach($ids as $id)
        {
            $student = Db::name('student')->find($id);
            $user = Db::name('user')->where('username',$student['xh'])->find();
            if($user){
                Db::name('user')->where('id', $user['id'])->update(['password' => md5($student['xh'])]);
            }else{
                $param['username']        = $student['xh'];
                $param['fullname']        = $student['name'];
                $param['password']        = md5($student['xh']);
                $role = RoleService::getByRolename('学生');
                $param['roleids']=[$role['id']];
                UserService::add($param);
            }
        }
        return $ids;
    }
}
