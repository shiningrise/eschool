<?php
namespace app\chengji\validate;

use think\Validate;
use app\chengji\service\Kaoshi_shichangService;

class Kaoshi_shichangValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'name'        => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'num.require'   => '请输入试场号',
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['num'],
        'edit'   => ['id', 'num'],
        'del'    => ['id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = Kaoshi_shichangService::info($id);
        return true;
    }
}

