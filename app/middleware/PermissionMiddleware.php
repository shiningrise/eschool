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
use think\facade\Log;
use app\admin\service\ModuleService;

class PermissionMiddleware
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
        $module_url       = request_pathinfo();
        $user_id = user_id();
        $api_white_list = Config::get('admin.api_white_list');
        $urls = ModuleService::getModuleUrlByUserId($user_id);
        if(!in_array($module_url, $api_white_list) && !in_array($module_url, $urls))
        {
            //http_response_code(403);
            return error("无权访问");
            exit();
        }
		$response = $next($request);
        return $response;
    }
}
