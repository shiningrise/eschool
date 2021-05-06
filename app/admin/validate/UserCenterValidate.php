<?php
/*
 * @Description  : 用户个人中心验证器
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-05
 * @LastEditTime : 2021-04-13
 */

namespace app\admin\validate;

use think\Validate;
use think\facade\Db;
use app\admin\service\UserService;

class UserCenterValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id'  => ['require', 'checkAdminUser'],
        'username'       => ['require', 'checkUsername', 'length' => '2,32'],
        'nickname'       => ['require', 'length' => '1,32'],
        'password_old'   => ['require'],
        'password_new'   => ['require', 'length' => '6,18'],
        'phone'          => ['mobile', ],
        'email'          => ['email', ],
        'avatar'         => ['require', 'file', 'image', 'fileExt' => 'jpg,png,gif', 'fileSize' => '51200'],
    ];

    // 错误信息
    protected $message = [
        'id.require'  => '缺少参数：用户id',
        'username.require'       => '请输入账号',
        'username.length'        => '账号长度为2至32个字符',
        'nickname.require'       => '请输入昵称',
        'nickname.length'        => '昵称长度为1至32个字符',
        'password_old.require'   => '请输入旧密码',
        'password_new.require'   => '请输入新密码',
        'password_new.length'    => '新密码长度为6至18个字符',
        'phone.mobile'           => '请输入正确的手机号码',
        'email.email'            => '请输入正确的邮箱地址',
        'avatar.require'         => '请选择图片',
        'avatar.file'            => '请选择图片文件',
        'avatar.image'           => '请选择图片格式文件',
        'avatar.fileExt'         => '请选择jpg、png、gif格式图片',
        'avatar.fileSize'        => '请选择大小小于50kb的图片',
    ];

    // 验证场景
    protected $scene = [
        'id'     => ['id'],
        'info'   => ['id'],
        'edit'   => ['id', 'username', 'nickname', 'phone', 'email'],
        'pwd'    => ['id', 'password_old', 'password_new', 'phone'],
        'avatar' => ['id', 'avatar'],
        'log'    => ['id'],
    ];

    // 自定义验证规则：用户是否存在
    protected function checkAdminUser($value, $rule, $data = [])
    {
        $id = $value;

        $admin_user = UserService::info($id);

        if ($admin_user['is_delete'] == 1) {
            return '用户已被删除：' . $id;
        }

        return true;
    }

    // 自定义验证规则：账号是否已存在
    protected function checkUsername($value, $rule, $data = [])
    {
        $id = $data['id'];
        $username      = $data['username'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $id)
            ->where('username', '=', $username)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '账号已存在：' . $username;
        }

        return true;
    }

    // 自定义验证规则：昵称是否已存在
    protected function checkNickname($value, $rule, $data = [])
    {
        $id = $data['id'];
        $nickname      = $data['nickname'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $id)
            ->where('nickname', '=', $nickname)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '昵称已存在：' . $nickname;
        }

        return true;
    }

    // 自定义验证规则：手机是否已存在
    protected function checkPhone($value, $rule, $data = [])
    {
        $id = $data['id'];
        $phone         = $data['phone'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $id)
            ->where('phone', '=', $phone)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '手机已存在：' . $phone;
        }

        return true;
    }

    // 自定义验证规则：邮箱是否已存在
    protected function checkEmail($value, $rule, $data = [])
    {
        $id = $data['id'];
        $email         = $data['email'];

        $admin_user = Db::name('admin_user')
            ->field('id')
            ->where('id', '<>', $id)
            ->where('email', '=', $email)
            ->where('is_delete', '=', 0)
            ->find();

        if ($admin_user) {
            return '邮箱已存在：' . $email;
        }

        return true;
    }
}
