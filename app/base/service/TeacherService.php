<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;
use app\admin\service\RoleService;
use app\admin\service\UserService;

class TeacherService{
    public static function getList()
    {
        $teacher = Db::name('teacher')->select()->toArray();
        return $teacher;
    }
    public static function getByName($name)
    {
        $where[] = ['name', '=',  $name];
        $teacher = Db::name('teacher')
            ->where($where)
            ->find();
        return $teacher;
    }

    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,qy_userid,tel,remark,is_atbook,is_atschool,sort,name,username';
        }

        if (empty($order)) {
            $order = ['sort' => 'asc'];
        }

        $count = Db::name('teacher')
            ->where($where)
            ->count('id');

        $list = Db::name('teacher')
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
        $teacher = Db::name('teacher')
            ->where($where)
            ->find();
        return $teacher;
    }

    public static function add($param)
    {
        $id = Db::name('teacher')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('teacher')
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
        Db::name('teacher')->delete($id);
        return $id;
    }

    public static function multiDelete($ids)
    {
        foreach($ids as $id)
        {
            Db::name('teacher')->delete($id);
        }
        return $ids;
    }

    public static function init($ids)
    {
        foreach($ids as $id)
        {
            $teacher = Db::name('teacher')->find($id);
            $user = Db::name('user')->where('username',$teacher['username'])->find();
            if($user){
                Db::name('user')->where('id', $user['id'])->update(['password' => md5('123456')]);
            }else{
                $param['username']        = $teacher['username'];
                $param['fullname']        = $teacher['name'];
                $role = RoleService::getByRolename('教师');
                $param['roleids']=[$role['id']];
                UserService::add($param);
            }
        }
        return $ids;
    }

}
