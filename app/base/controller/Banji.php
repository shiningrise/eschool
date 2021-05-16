<?php
namespace app\base\controller;

use think\facade\Request;
use app\BaseController;
use app\base\model\BanjiModel;
use app\base\service\BanjiService;
use app\base\validate\BanjiValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Banji extends BaseController
{
    /**
     * @Apidoc\Title("列表")
     * @Apidoc\Param("page", type="int", default="1", desc="页码")
     * @Apidoc\Param("limit", type="int", default="10", desc="pagesize")
     * @Apidoc\Param("sort_field", type="string", default="", desc="sort_field")
     * @Apidoc\Param("sort_type", type="string", default="", desc="sort_type")
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
        $sort_field = Request::param('sort_field/s ', '');
        $sort_type  = Request::param('sort_type/s', '');

        $where = [];
        $ji               = Request::param('ji/d', '');
        if ($ji) {
            $where[] = ['ji', '=',$ji];
        }
        $name               = Request::param('name/s', '');
        if ($name) {
            $where[] = ['name', 'LIKE','%' .$name. '%'];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = BanjiService::list($where, $page, $limit, $order);

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
        validate(BanjiValidate::class)->scene('info')->check($param);
        $data = BanjiService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("is_graduated", type="string", default="", desc="毕业？")
     * @Apidoc\Param("kelei", type="string", default="", desc="科类")
     * @Apidoc\Param("bh", type="string", default="", desc="班号")
     * @Apidoc\Param("ji", type="string", default="", desc="级")
     * @Apidoc\Param("xueduan", type="string", default="", desc="学段")
     * @Apidoc\Param("bzr_id", type="string", default="", desc="班主任")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("bianhao", type="string", default="", desc="编号")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['beizhu'] 			= Request::param('beizhu/s', '');
        $param['is_graduated'] 			= Request::param('is_graduated/s', '');
        $param['kelei'] 			= Request::param('kelei/s', '');
        $param['bh'] 			= Request::param('bh/s', '');
        $param['ji'] 			= Request::param('ji/s', '');
        $param['xueduan'] 			= Request::param('xueduan/s', '');
        $param['bzr_name'] 			= Request::param('bzr_name/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['bianhao'] 			= Request::param('bianhao/s', '');
        validate(BanjiValidate::class)->scene('add')->check($param);
        $data = BanjiService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("beizhu", type="string", default="", desc="备注")
     * @Apidoc\Param("is_graduated", type="string", default="", desc="毕业？")
     * @Apidoc\Param("kelei", type="string", default="", desc="科类")
     * @Apidoc\Param("bh", type="string", default="", desc="班号")
     * @Apidoc\Param("ji", type="string", default="", desc="级")
     * @Apidoc\Param("xueduan", type="string", default="", desc="学段")
     * @Apidoc\Param("bzr_id", type="string", default="", desc="班主任")
     * @Apidoc\Param("name", type="string", default="", desc="名称")
     * @Apidoc\Param("bianhao", type="string", default="", desc="编号")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['beizhu']			= Request::param('beizhu/s', '');
        $param['is_graduated']			= Request::param('is_graduated/s', '');
        $param['kelei']			= Request::param('kelei/s', '');
        $param['bh']			= Request::param('bh/s', '');
        $param['ji']			= Request::param('ji/s', '');
        $param['xueduan']			= Request::param('xueduan/s', '');
        $param['bzr_name'] 			= Request::param('bzr_name/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['bianhao']			= Request::param('bianhao/s', '');

        validate(BanjiValidate::class)->scene('edit')->check($param);

        $data = BanjiService::edit($param);

        return success($data);
    }

        /**
     * @Apidoc\Title("删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="int", default="1", desc="id")
     * @Apidoc\Returned(ref="return")
     */
    public function del()
    {
        $param['id'] = Request::param('id/d', '');

        validate(BanjiValidate::class)->scene('del')->check($param);

        $data = BanjiService::del($param['id']);

        return success($data);
    }
}

