<?php
/*
 * @Description  : 角色验证器
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\service\PermissionService;

class PermissionValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'          => ['require', 'checkId'],
        'name'        => ['require'],
        'code'        => ['require'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：权限ID',
        'name.require'   => '请输入权限名称',
        'code.require'   => '请输入权限代码',
    ];

    // 验证场景
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['name','code','parent_id'],
        'edit'   => ['id', 'name','code','parent_id'],
        'del'    => ['id'],
    ];

    // 自定义验证规则：角色是否存在
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;

        $data = PermissionService::info($id);

        return true;
    }
}
