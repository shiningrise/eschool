<?php
namespace app\chengji\validate;

use think\Validate;
use app\chengji\service\Kaoshi_xuekeService;

class Kaoshi_xuekeValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'kaoshi_id'        => ['require'],
        'xueke_id'        => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'kaoshi_id.require'   => '请输入名称考试ID',
		'xueke_id.require' =>'请输入学科ID'
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['kaoshi_id','xueke_id'],
        'edit'   => ['id', 'kaoshi_id','xueke_id'],
        'del'    => ['id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = Kaoshi_xuekeService::info($id);
        return true;
    }
}

