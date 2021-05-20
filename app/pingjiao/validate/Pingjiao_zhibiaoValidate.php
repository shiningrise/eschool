<?php
namespace app\pingjiao\validate;

use think\Validate;
use app\pingjiao\service\Pingjiao_zhibiaoService;

class Pingjiao_zhibiaoValidate extends Validate
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
        $data = Pingjiao_zhibiaoService::info($id);
        return true;
    }
}

