<?php
namespace app\admin\controller;

use think\facade\Request;
use app\BaseController;
use app\admin\model\UserlogModel;
use app\admin\service\UserlogService;
use app\admin\validate\UserlogValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("管理")
 * @Apidoc\Group("admin")
 */
class Userlog extends BaseController
{
    /**
     * @Apidoc\Title("列表")
     * @Apidoc\Param("page", type="int", default="1", desc="页码")
     * @Apidoc\Param("limit", type="int", default="10", desc="pagesize")
     * @Apidoc\Returned(ref="return"),
     * @Apidoc\Returned("data", type="object", desc="返回数据",
     *      @Apidoc\Returned(ref="returnPaging"),
     *      @Apidoc\Returned("list", type="array", desc="数据列表")
     * )
     */
    public function list()
    {
        $page       = Request::param('page/d', 1);
        $limit      = Request::param('limit/d', 5);
        // $username   = Request::param('username/s', '');
        // $fullname   = Request::param('fullname/s', '');
        // $module_url = Request::param('module_url/s', '');
        // $module_name= Request::param('module_name/s', '');
        // $request_ip   = Request::param('request_ip/s', '');
        $where = [];
        // if ($username) {
        //     $where[] = ['username', 'like', '%' . $username . '%'];
        // }
        // if ($fullname) {
        //     $where[] = ['fullname', 'like', '%' . $fullname . '%'];
        // }
        // if ($module_url) {
        //     $where[] = ['module_url', 'like', '%' . $module_url . '%'];
        // }
        // if ($module_name) {
        //     $where[] = ['module_name', 'like', '%' . $module_name . '%'];
        // }
        // if ($request_ip) {
        //     $where[] = ['request_ip', '=', $request_ip];
        // }
        $order = [];
        $data = UserlogService::list($where, $page, $limit, $order);

        return success($data);
    }

    /**
     * @Apidoc\Title("信息")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function info()
    {
        $param['id'] = Request::param('id/d', '');
        validate(UserlogValidate::class)->scene('info')->check($param);
        $data = UserlogService::info($param['id']);

        return success($data);
    }
}
