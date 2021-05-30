<?php
/*
 * @Description  : 日志管理中间件
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-06
 * @LastEditTime : 2021-04-07
 */

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;
use app\admin\service\UserlogService;

class UserlogMiddleware
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $is_log = Config::get('admin.is_log');

        if ($is_log) {
            $user_id = user_id();
            if ($user_id) {
                $response_data = $response->getData();
                
                if (isset($response_data['code'])) {
                    $log['response_code'] = $response_data['code'];
                }
                if (isset($response_data['msg'])) {
                    $log['response_msg'] = substr($response_data['msg'],0,250);
                } else {
                    if (isset($response_data['message'])) {
                        $log['response_msg'] = substr($response_data['message'],0,250);
                    }
                }
                
                $log['user_id'] = $user_id;
                UserlogService::add($log);
            }
        }

        return $response;
    }
}
