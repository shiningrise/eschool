<?php
namespace app\chengji\validate;

use think\Validate;
use app\chengji\service\Kaoshi_jiankaoService;

class Kaoshi_jiankaoValidate extends Validate
{
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'name'        => ['require'],
    ];
	
    protected $message = [
        'id.require' => '缺少参数：ID',
        'kaoshi_id.require' => '缺少参数：考试ID',
        'shichang_id.require' => '缺少参数：试场ID',
        'xueke_id.require' => '缺少参数：学科ID',
        'teacher_id.require' => '缺少参数：教师ID',
        'name.require'   => '请输入名称',
    ];
	
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name'],
        'edit'   => ['id', 'name'],
        'del'    => ['id'],
        'import'    => ['kaoshi_id','shichang_id','xueke_id','teacher_id'],
    ];
	
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;
        $data = Kaoshi_jiankaoService::info($id);
        return true;
    }
}

