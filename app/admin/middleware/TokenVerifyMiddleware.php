<?php
/*
 * @Description  : Token验证中间件
 * @Author       : wxy
 * @Date         : 2021-04-29
 * @LastEditTime : 2021-04-29
 */

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use think\facade\Config;
use app\admin\service\TokenService;

class TokenVerifyMiddleware
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
        $menu_url       = request_pathinfo();
        $api_white_list = Config::get('admin.api_white_list');

        // if (!in_array($menu_url, $api_white_list)) {
        //     $token = token();

        //     if (empty($token)) {
        //         exception('Requests Headers：Token must');
        //     }

        //     $user_id = user_id();

        //     if (empty($user_id)) {
        //         exception('Requests Headers：UserId must');
        //     }

        //     TokenService::verify($token, $user_id);
        // }

        return $next($request);
    }
}
