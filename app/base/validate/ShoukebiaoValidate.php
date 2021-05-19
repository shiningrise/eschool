<?php
namespace app\base\validate;

use think\Validate;
use app\base\service\ShoukebiaoService;

class ShoukebiaoValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'banji_id'    => ['require'],
        'xueke_id'    => ['require'],
        'teacher_id'  => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
    ];

    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['banji_id','xueke_id','teacher_id'],
        'edit'   => ['id'],
        'del'    => ['id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = ShoukebiaoService::info($id);
        return true;
    }
}

