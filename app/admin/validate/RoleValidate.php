<?php
/*
 * @Description  : 角色验证器
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\service\RoleService;

class RoleValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'    => ['require', 'checkId'],
        'rolename'        => ['require'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：角色ID',
        'rolename.require'   => '请输入角色名称',
    ];

    // 验证场景
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'add'    => ['rolename','beizhu'],
        'edit'   => ['id', 'rolename','beizhu'],
        'del'   => ['id'],
    ];

    // 自定义验证规则：角色是否存在
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;

        $role = RoleService::info($id);

        return true;
    }
}
