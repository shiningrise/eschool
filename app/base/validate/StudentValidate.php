<?php
namespace app\base\validate;

use think\Validate;
use think\facade\Db;
use app\base\service\StudentService;

class StudentValidate extends Validate
{
    protected $rule = [
        'id'          => ['require'],
        'xh'        => ['require','checkXh'],
        'name'        => ['require'],
        'banji_id'        => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'name.require'   => '请输入名称',
        'xh.require'   => '请输入学号',
        'banji_id.require'   => '请输入班级ID',
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name'],
        'edit'   => ['id', 'name'],
        'del'    => ['id'],
        'import'    => ['name','xh','banji_id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = StudentService::info($id);
        return true;
    }

    protected function checkXh($value, $rule, $data = [])
    {
        $id         = isset($data['id']) ? $data['id'] : '';
        $xh   = $data['xh'];

        if ($id) {
            $where[] = ['id', '<>', $id];
        }
        $where[] = ['xh', '=', $xh];
        //$where[] = ['is_delete', '=', 0];

        $student = Db::name('student')
            ->field('id')
            ->where($where)
            ->find();

        if ($student) {
            return '学号已存在：' . $xh;
        }

        return true;
    }
}

