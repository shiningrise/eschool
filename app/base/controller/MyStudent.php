<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\BanjiModel;
use app\base\service\BanjiService;
use app\base\service\StudentService;
use app\base\service\Service;
use app\base\validate\BanjiValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class MyStudent extends BaseController
{
	/**
	 * @Apidoc\Title("根据班主任用户ID获取班级列表")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据",
	 *      @Apidoc\Returned(ref="returnPaging"),
	 *      @Apidoc\Returned("list", type="array", desc="数据列表")
	 * )
	 */
	public function listBanjiByBzrUserId()
	{
	    $user_id = user_id();
	    $data = BanjiService::listByBzrUserId($user_id);
	    return success($data);
	}
	
	/**
	 * @Apidoc\Title("根据班级ID获取学生列表")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据",
	 *      @Apidoc\Returned(ref="returnPaging"),
	 *      @Apidoc\Returned("list", type="array", desc="数据列表")
	 * )
	 */
	public function listStudentByBanjiId()
	{
		$banji_id       = Request::param('banji_id/d', 0);
	    $data = StudentService::listByBanjiId($banji_id);
	    return success($data);
	}
	
	/**
	 * @Apidoc\Title("批量删除")
	 * @Apidoc\Method("POST")
	 * @Apidoc\Header(ref="headerAdmin")
	 * @Apidoc\Param("ids", type="int[]", default="1", desc="id数组")
	 * @Apidoc\Returned(ref="return")
	 */
	public function init()
	{
	    //$param['ids']    = input("post.permission_ids/a");
	    $param['ids']    = input();
	    $data = StudentService::init($param['ids']);
	
	    return success($data);
	}
}

