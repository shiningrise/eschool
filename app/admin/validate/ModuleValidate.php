<?php
/*
 * @Description  : 角色验证器
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\service\ModuleService;

class ModuleValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'name'        => ['require'],
        'parent_id'   => ['require'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：ID',
        'name.require'   => '请输入名称',
        'parent_id.require'         => '请输入父ID',
    ];

    // 验证场景
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name','parent_id'],
        'edit'   => ['id', 'name','parent_id'],
        'del'    => ['id'],
    ];

    // 自定义验证规则：是否存在
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;

        $data = ModuleService::info($id);

        return true;
    }
}
