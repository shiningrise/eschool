<?php
namespace app\chengji\controller;

use think\facade\Request;
use think\facade\Log;
use think\facade\Db;
use app\BaseController;
use app\chengji\model\Kaoshi_kaoshengModel;
use app\chengji\service\Kaoshi_kaoshengService;
use app\chengji\service\Kaoshi_shichangService;
use app\chengji\service\KaoshiService;
use app\base\service\BanjiService;
use app\base\service\StudentService;
use app\chengji\validate\Kaoshi_kaoshengValidate;
use hg\apidoc\annotation as Apidoc;

/**
 * @Apidoc\Title("")
 * @Apidoc\Group("chengji")
 */
class KaoshiKaosheng extends BaseController
{
	/**
	 * @Apidoc\Title("生成试场座位表")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function makeZuowei(){
		$kaoshi_id  = Request::param('kaoshi_id/d', 1);
		$kaoshi = KaoshiService::info($kaoshi_id);
		$kaoshengs = Kaoshi_kaoshengService::listByKaoshiId($kaoshi_id);
		$shichangs = Kaoshi_shichangService::getByKaoshiId($kaoshi_id);
		$i=0;
		foreach($shichangs as $shichang){
			for($j=1;$j<=$shichang['renshu'];$j++){
				$kaoshengs[$i]['shichangnum']=$shichang['num'];
				$kaoshengs[$i]['zuoweihao']=str_pad($j,2,"0",STR_PAD_LEFT);
				$kaoshengs[$i]['zhunkaozhenghao']=$kaoshi['pernum'].$kaoshengs[$i]['shichangnum'].$kaoshengs[$i]['zuoweihao'];
				Db::name('kaoshi_kaosheng')
				    ->where('id',$kaoshengs[$i]['id'])
				    ->update($kaoshengs[$i]);
				$i++;
			}
		}
		return success();
	}
	/**
	 * @Apidoc\Title("随机生成考号")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function randKaohao(){
		$kaoshi_id  = Request::param('kaoshi_id/d', 1);
		$list = Kaoshi_kaoshengService::listByKaoshiId($kaoshi_id);
		foreach($list as $kaosheng){
			$kaosheng['xuhao']=rand();
			Db::name('kaoshi_kaosheng')
			    ->where('id',$kaosheng['id'])
			    ->update(['xuhao' => $kaosheng['xuhao']]);
		}
		$list = Kaoshi_kaoshengService::listByKaoshiId($kaoshi_id);
		$i = 1;
		foreach($list as $kaosheng){
			$kaosheng['xuhao']=$i;
			$i++;
			Db::name('kaoshi_kaosheng')
			    ->where('id',$kaosheng['id'])
			    ->update(['xuhao' => $kaosheng['xuhao']]);
		}
		return success();
	}
	/**
	 * @Apidoc\Title("批量添加考生")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Param("banjis", type="int[]", default="[]", desc="班级名称列表")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function batchAddStudent(){
		$kaoshi_id  = Request::param('kaoshi_id/d', 1);
		$banjis   	= input("post.banjis/a");
		foreach($banjis as $banjiName){
			$banji = BanjiService::getByName($banjiName);
			$students = StudentService::listByBanjiId($banji['id']);
			foreach($students as $student){
				Kaoshi_kaoshengService::save($kaoshi_id,$student);
			}
		}
		return success();
	}
	/**
	 * @Apidoc\Title("列出所有未毕业班级")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据",
	 *      @Apidoc\Returned(ref="returnPaging"),
	 * )
	 */
	public function getUngraduatedBanjis(){
		$data = BanjiService::getUngraduatedBanjis();
		return success($data);
	}
    /**
     * @Apidoc\Title("列表")
     * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
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
		$kaoshi_id  = Request::param('kaoshi_id/d', 1);
		
        $where = [];
		$where[] = ['kaoshi_id','=',$kaoshi_id];

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }else{
			$order = ['xuhao' => 'asc'];
		}
        $data = Kaoshi_kaoshengService::list($where, $page, $limit, $order);

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
        validate(Kaoshi_kaoshengValidate::class)->scene('info')->check($param);
        $data = Kaoshi_kaoshengService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("student_id", type="string", default="", desc="学生")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("mc2", type="string", default="", desc="段名次")
     * @Apidoc\Param("mc1", type="string", default="", desc="班名次")
     * @Apidoc\Param("zongfen", type="string", default="", desc="总分")
     * @Apidoc\Param("active", type="string", default="", desc="启用")
     * @Apidoc\Param("zhunkaozhenghao", type="string", default="", desc="准考证号")
     * @Apidoc\Param("zuoweihao", type="string", default="", desc="座位号")
     * @Apidoc\Param("shichangnum", type="string", default="", desc="试场号")
     * @Apidoc\Param("xuhao", type="string", default="", desc="序号")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['student_id'] 			= Request::param('student_id/s', '');
        $param['banji_id'] 			= Request::param('banji_id/s', '');
        $param['kaoshi_id'] 			= Request::param('kaoshi_id/s', '');
        $param['mc2'] 			= Request::param('mc2/s', '');
        $param['mc1'] 			= Request::param('mc1/s', '');
        $param['zongfen'] 			= Request::param('zongfen/s', '');
        $param['active'] 			= Request::param('active/s', '');
        $param['zhunkaozhenghao'] 			= Request::param('zhunkaozhenghao/s', '');
        $param['zuoweihao'] 			= Request::param('zuoweihao/s', '');
        $param['shichangnum'] 			= Request::param('shichangnum/s', '');
        $param['xuhao'] 			= Request::param('xuhao/s', '');
        validate(Kaoshi_kaoshengValidate::class)->scene('add')->check($param);
        $data = Kaoshi_kaoshengService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("student_id", type="string", default="", desc="学生")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("mc2", type="string", default="", desc="段名次")
     * @Apidoc\Param("mc1", type="string", default="", desc="班名次")
     * @Apidoc\Param("zongfen", type="string", default="", desc="总分")
     * @Apidoc\Param("active", type="string", default="", desc="启用")
     * @Apidoc\Param("zhunkaozhenghao", type="string", default="", desc="准考证号")
     * @Apidoc\Param("zuoweihao", type="string", default="", desc="座位号")
     * @Apidoc\Param("shichangnum", type="string", default="", desc="试场号")
     * @Apidoc\Param("xuhao", type="string", default="", desc="序号")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['student_id']			= Request::param('student_id/s', '');
        $param['banji_id']			= Request::param('banji_id/s', '');
        $param['kaoshi_id']			= Request::param('kaoshi_id/s', '');
        $param['mc2']			= Request::param('mc2/s', '');
        $param['mc1']			= Request::param('mc1/s', '');
        $param['zongfen']			= Request::param('zongfen/s', '');
        $param['active']			= Request::param('active/s', '');
        $param['zhunkaozhenghao']			= Request::param('zhunkaozhenghao/s', '');
        $param['zuoweihao']			= Request::param('zuoweihao/s', '');
        $param['shichangnum']			= Request::param('shichangnum/s', '');
        $param['xuhao']			= Request::param('xuhao/s', '');

        validate(Kaoshi_kaoshengValidate::class)->scene('edit')->check($param);

        $data = Kaoshi_kaoshengService::edit($param);

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

        validate(Kaoshi_kaoshengValidate::class)->scene('del')->check($param);

        $data = Kaoshi_kaoshengService::del($param['id']);

        return success($data);
    }
}

