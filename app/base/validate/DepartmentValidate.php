<?php
namespace app\base\validate;

use think\Validate;
use app\base\service\DepartmentService;

class DepartmentValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'name'        => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'name.require'   => '请输入名称',
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name'],
        'edit'   => ['id', 'name'],
        'del'    => ['id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = DepartmentService::info($id);
        return true;
    }
}

