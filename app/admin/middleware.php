<?php
// 这是系统自动生成的middleware定义文件
return [
    \app\admin\middleware\TokenVerifyMiddleware::class,
    \app\admin\middleware\UserlogMiddleware::class,
    \app\admin\middleware\PermissionMiddleware::class,
];
