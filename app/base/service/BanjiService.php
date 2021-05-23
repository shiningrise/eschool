<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Log;
use app\admin\model\UserModel;
use app\admin\service\UserService;
use app\admin\service\RoleService;
use app\base\model\TeacherModel;

class BanjiService{
	//根据班主任用户ID获取班级列表
	public static function listByBzrUserId($user_id)
	{
		$user = UserModel::find($user_id);
		$teacher = TeacherModel::where('username',$user['username'])->find();
		if($teacher){
			$where = [];
			$where[] = ['bzr_id', '=', $teacher['id']];
			$where[] = ['is_graduated', '=', false];
			$data = Db::name('banji')->where($where)->select()->toArray();
			return $data;
		}
		$data=[];
	    return $data;
	}
	//根据班级ID获取班级人数
	public static function getBanjiRenshu($banji_id)
    {
        $where[] = ['banji_id', '=', $banji_id];
        $renshu = Db::name('student')->where($where)->count();
        return $renshu;
    }
	
    public static function getUngraduatedBanjis()
    {
        $where[] = ['is_graduated', '=', 0];
        $order = ['bianhao' => 'asc'];
        $jiList = Db::table('banji')->where($where)->order($order)->select()->toArray();
        return $jiList;
    }

    public static function getJi()
    {
        $where[] = ['is_graduated', '=', 0];
        $jiList = Db::table('banji')->distinct(true)->field('ji')->where($where)->select()->toArray();
        return $jiList;
    }

    public static function getByName($name)
    {
        $where[] = ['name', '=',  $name];
        $banji = Db::name('banji')
            ->where($where)
            ->find();
        return $banji;
    }

    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,beizhu,is_graduated,kelei,bh,ji,xueduan,bzr_id,name,bianhao';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('banji')
            ->where($where)
            ->count('id');

        $list = Db::name('banji')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();
        foreach ($list as $k => $v) {
            $list[$k]['bzr_name'] = '';
            $bzr = TeacherService::info($v['bzr_id']);
            if ($bzr) {
                $list[$k]['bzr_name'] = $bzr['name'];
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
        $banji = Db::name('banji')
            ->where($where)
            ->find();
        $bzr = TeacherService::info($banji['bzr_id']);
        if ($bzr) {
            $banji['bzr_name'] = $bzr['name'];
        }
        return $banji;
    }

    public static function add($param)
    {
        $bzr_name = $param['bzr_name'];
        unset($param['bzr_name']);
        $teacher = TeacherService::getByName($bzr_name);
        if($teacher){
            $param['bzr_id']=$teacher['id'];
        }
        $id = Db::name('banji')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $bzr_name = $param['bzr_name'];
        unset($param['bzr_name']);
        $teacher = TeacherService::getByName($bzr_name);
        if($teacher){
            $param['bzr_id']=$teacher['id'];
        }
        $id = $param['id'];
        $res = Db::name('banji')
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
        Db::name('banji')->delete($id);
        return $id;
    }

	public static function initBzrRole($ids)
    {
        foreach($ids as $id)
        {
            $banji = Db::name('banji')->find($id);
			if(!$banji['bzr_id'])
				continue;
			$teacher = Db::name('teacher')->find($banji['bzr_id']);
            //$user = Db::name('user')->where('username',$teacher['username'])->find();
			$user = UserModel::where('username',$teacher['username'])->find();
			$role = RoleService::getByRolename('班主任');
			$found = false;
			foreach($user->roles as $dbrole)
			{
				if($dbrole['id']==$role['id'])
				{
					$found = true;
				}
			}
			if($found == false)
				$user->roles()->save($role['id']);
        }
        return $ids;
    }
}
