<?php
namespace app\base\validate;

use think\Validate;
use think\facade\Db;

use app\base\service\TeacherService;

class TeacherValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'username'    => ['require', 'checkUsername', 'length' => '2,32'],
        'name'        => ['require', 'checkName','length' => '2,32'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'username.require'   => '请输入用户名',
        'name.require'   => '请输入名称',
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name','username'],
        'edit'   => ['id', 'name'],
        'del'    => ['id'],
        'import'    => ['name','username'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = TeacherService::info($id);
        return true;
    }

    protected function checkUsername($value, $rule, $data = [])
    {
        $id         = isset($data['id']) ? $data['id'] : '';
        $username   = $data['username'];

        if ($id) {
            $where[] = ['id', '<>', $id];
        }
        $where[] = ['username', '=', $username];
        //$where[] = ['is_delete', '=', 0];

        $teacher = Db::name('teacher')
            ->field('id')
            ->where($where)
            ->find();

        if ($teacher) {
            return '账号已存在：' . $username;
        }

        return true;
    }

    protected function checkName($value, $rule, $data = [])
    {
        $id         = isset($data['id']) ? $data['id'] : '';
        $name   = $data['name'];

        if ($id) {
            $where[] = ['id', '<>', $id];
        }
        $where[] = ['name', '=', $name];
        //$where[] = ['is_delete', '=', 0];

        $teacher = Db::name('teacher')
            ->field('id')
            ->where($where)
            ->find();

        if ($teacher) {
            return '姓名已存在：' . $name;
        }

        return true;
    }
}

