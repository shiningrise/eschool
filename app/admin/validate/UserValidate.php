<?php
/*
 * @Description  : 角色验证器
 * @Author       : wxy
 * @Date         : 2021-04-28
 * @LastEditTime : 2021-04-28
 */

namespace app\admin\validate;

use think\Validate;
use think\facade\Db;

use app\admin\service\UserService;

class UserValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'    => ['require', 'checkId'],
        'username'      => ['require','checkUsername'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：用户ID',
        'username.require'   => '请输入用户名',
        'avatar.require'        => '请选择图片',
        'avatar.file'           => '请选择图片文件',
        'avatar.image'          => '请选择图片格式文件',
        'avatar.fileExt'        => '请选择jpg、png、gif格式图片',
        'avatar.fileSize'       => '请选择大小小于100kb图片',
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
        'avatar'  => ['id', 'avatar'],
    ];

    // 自定义验证规则：角色是否存在
    protected function checkId($value, $rule, $data = [])
    {
        $id = $value;

        $role = UserService::info($id);

        return true;
    }

    protected function checkUsername($value, $rule, $data = [])
    {
        $id         = isset($data['id']) ? $data['id'] : '';
        $username   = $data['username'];

        if ($id) {
            $where[] = ['id', '<>', $id];
        }
        $where[] = ['username', '=', $username];
        //$where[] = ['is_delete', '=', 0];

        $teacher = Db::name('user')
            ->field('id')
            ->where($where)
            ->find();

        if ($teacher) {
            return '账号已存在：' . $username;
        }

        return true;
    }
}
