<?php
/*
 * @Description  : 日志管理中间件
 * @Author       : https://github.com/skyselang
 * @Date         : 2020-05-06
 * @LastEditTime : 2021-04-07
 */

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;
use app\admin\service\LogService;

class LogMiddleware
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
            $id = user_id();

            if ($id) {
                $response_data = $response->getData();
                
                if (isset($response_data['code'])) {
                    $log['response_code'] = $response_data['code'];
                }
                if (isset($response_data['msg'])) {
                    $log['response_msg'] = $response_data['msg'];
                } else {
                    if (isset($response_data['message'])) {
                        $log['response_msg'] = $response_data['message'];
                    }
                }
                
                $log['id'] = $id;
                LogService::add($log);
            }
        }

        return $response;
    }
}
