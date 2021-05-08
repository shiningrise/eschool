<?php
/*
 * @Description  : 日志管理验证器
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-06
 * @LastEditTime : 2021-04-16
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\service\LogService;

class LogValidate extends Validate
{
    // 验证规则
    protected $rule = [
        'id' => ['require'],
    ];

    // 错误信息
    protected $message = [
        'id.require' => '缺少参数：日志管理id',
    ];

    // 验证场景
    protected $scene = [
        'id'   => ['id'],
        'info' => ['id'],
        'del' => ['id'],
    ];
}
