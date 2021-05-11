<?php
/*
 * @Description  : 角色验证器
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\service\UserService;

class UserValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'    => ['require', 'checkId'],
        'username'        => ['require'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：用户ID',
        'username.require'   => '请输入用户名',
    ];

    // 验证场景
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'login'   => ['username', 'password'],
        'add'    => ['username','fullname','beizhu'],
        'edit'   => ['id', 'username','fullname','beizhu'],
        'del'   => ['id'],
        'pwd'   => ['id'],
    ];

    // 自定义验证规则：角色是否存在
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;

        $role = UserService::info($id);

        return true;
    }
}
