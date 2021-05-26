<?php
namespace app\chengji\controller;

use think\facade\Request;
use think\facade\Log;
use think\facade\Db;
use think\facade\Env;
use think\facade\Filesystem;
use think\facade\Config;
use app\BaseController;
use app\chengji\model\Kaoshi_jiankaoModel;
use app\chengji\service\Kaoshi_jiankaoService;
use app\chengji\service\Kaoshi_xuekeService;
use app\chengji\service\Kaoshi_shichangService;
use app\base\service\TeacherService;
use app\chengji\validate\Kaoshi_jiankaoValidate;
use hg\apidoc\annotation as Apidoc;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @Apidoc\Title("监考")
 * @Apidoc\Group("chengji")
 */
class KaoshiJiankao extends BaseController
{
	/**
	 * @Apidoc\Title("列表")
	 * @Apidoc\Param("JiankaoData", type="[]", default="0", desc="数组")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据",
	 * )
	 */
	public function save()
	{
		$data   = input("post.jiankaoData/a");
		$kaoshi_id   = Request::param('kaoshi_id/d', 0);
		Kaoshi_jiankaoService::save($kaoshi_id,$data);
		return success();
	}
	
	/**
	 * @Apidoc\Title("列表")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试Id")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据",
	 * )
	 */
	public function getJiaokaoTable()
	{
		$kaoshi_id       = Request::param('kaoshi_id/d', 1);
		$data = Kaoshi_jiankaoService::getJiaokaoTable($kaoshi_id);
		return success($data);
	}
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

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = Kaoshi_jiankaoService::list($where, $page, $limit, $order);

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
        validate(Kaoshi_jiankaoValidate::class)->scene('info')->check($param);
        $data = Kaoshi_jiankaoService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("int", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("shichang_id", type="string", default="", desc="试场")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['int'] 			= Request::param('int/s', '');
        $param['teacher_id'] 			= Request::param('teacher_id/s', '');
        $param['shichang_id'] 			= Request::param('shichang_id/s', '');
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['kaoshi_id'] 			= Request::param('kaoshi_id/s', '');
        validate(Kaoshi_jiankaoValidate::class)->scene('add')->check($param);
        $data = Kaoshi_jiankaoService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("int", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("shichang_id", type="string", default="", desc="试场")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['int']			= Request::param('int/s', '');
        $param['teacher_id']			= Request::param('teacher_id/s', '');
        $param['shichang_id']			= Request::param('shichang_id/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['kaoshi_id']			= Request::param('kaoshi_id/s', '');

        validate(Kaoshi_jiankaoValidate::class)->scene('edit')->check($param);

        $data = Kaoshi_jiankaoService::edit($param);

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

        validate(Kaoshi_jiankaoValidate::class)->scene('del')->check($param);

        $data = Kaoshi_jiankaoService::del($param['id']);

        return success($data);
    }
	
	/**
	 * @Apidoc\Title("下载导入模板")
	 * @Apidoc\Method("GET")
	 * @Apidoc\Header(ref="headerAdmin")
	 * @Apidoc\Returned(ref="return")
	 */
	public function download()
	{
		// download是系统封装的一个助手函数
	    $filepath = Config::get('filesystem.disks.public.root').'/download/jiankao.xlsx';
	    return download($filepath , '监考导入模板.xlsx');
	}
	
	/**
	 * @Apidoc\Title("导入")
	 * @Apidoc\Method("POST")
	 * @Apidoc\Header(ref="headerAdmin")
	 * @Apidoc\Param("excel_file", type="file", desc="上传的文件")
	 * @Apidoc\Returned(ref="return")
	 */
	public function import()
	{
		$kaoshi_id = Request::param('kaoshi_id/d', '');
	    $user_id = user_id();     
	    //ajax 文件跨域 验证
	    $request_method = $_SERVER['REQUEST_METHOD'];
	    if ($request_method === 'OPTIONS') {
	        header('Access-Control-Allow-Origin:*');
	        header('Access-Control-Allow-Credentials:true');
	        header('Access-Control-Allow-Methods:GET, POST, OPTIONS');
	        header('Access-Control-Max-Age:1728000');
	        header('Content-Type:text/plain charset=UTF-8');
	        header('Content-Length: 0',true);
	        header('status: 204');
	        header('HTTP/1.0 204 No Content');
	    }
	
	    $file = request()->file('excel_file');
	    if(!$file){
	         return error('请上传文件'); 
	    }
	    // 使用验证器验证上传的文件
	    validate(
	        [
	            'file' => [
	                // 限制文件大小(单位b)，这里限制为4M
	                'fileSize' => 4 * 1024 * 1024,
	                // 限制文件后缀，多个后缀以英文逗号分割
	                'fileExt'  => 'xlsx,xls'
	            ]
	        ],
	        [
	            'file.fileSize' => '文件太大',
	            'file.fileExt' => '不支持的文件后缀',
	        ]
	    )->check(['file' => $file]);
	
	    $savename = Filesystem::disk('public')->putFile('excel', $file);
	    $filenameExt = getExt($savename);        
	    if($filenameExt=="xlsx"){
	        $objReader = IOFactory::createReader('Xlsx');
	    }else{
	        $objReader = IOFactory::createReader('Xls');
	    }
	    $filepath = Config::get('filesystem.disks.public.root').'/'.$savename;
	    $objPHPExcel = $objReader->load($filepath);
	
	    // //读取默认工作表
	    $worksheet = $objPHPExcel->getSheet(0);
	    // //取得一共有多少行
	    $allRow = $worksheet->getHighestRow();
		$allCol = $worksheet->getHighestColumn();
	    $data = [];
		$xueke = [];
		for ($i='C';$i<=$allCol;$i++){
			$xueke[] = $worksheet->getCell($i.'1')->getValue();
		}
		$xuekeList = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
		$xuekeArray = [];
		foreach($xuekeList as $xuekeItem){
			$xuekeArray[$xuekeItem['xueke_name']]=$xuekeItem['xueke_id'];
		}
	    for ($i = 2; $i <= $allRow; $i++)
	    {
	        $data = [];
	        $num = $worksheet->getCell('A'.$i)->getValue();
			$cloIndex = 0;
			for ($j='C';$j <= $allCol;$j++){
				$xuekeValue = $xueke[$cloIndex];
				$cloIndex++;
				if(array_key_exists($xuekeValue,$xuekeArray))
				{
					$data['xueke_id']=$xuekeArray[$xuekeValue];
				}else{
					continue;
				}
				$data['kaoshi_id'] = $kaoshi_id;
				$shichang = Kaoshi_shichangService::getOneByKaoshiIdAndNum($kaoshi_id,$num);
				if($shichang == null) {
					continue;
					//exception($num.'试场不存在');
				}
				$data['shichang_id'] = $shichang['id'];
				$teacherName = $worksheet->getCell($j.$i)->getValue();
				if(empty($teacherName)==false){
					$teacher = TeacherService::getByName($teacherName);
					if(empty($teacher)){ 
						continue;
						//exception($teacherName.'教师不存在');
					}
					$data['teacher_id'] = $teacher['id'];
				}else{
					continue;
				}
				
				
				//防止出现空白Excel导致mysql报错，对数据做下判断
				if(empty($num)){
				    //跳出循环
				    break;
				}
				$jiankao = Db::name('kaoshi_jiankao')->where('kaoshi_id',$data['kaoshi_id'])
										->where('shichang_id',$data['shichang_id'])
										->where('xueke_id',$data['xueke_id'])
										->find();
				if($jiankao == null){
					validate(Kaoshi_jiankaoValidate::class)->scene('import')->check($data);
					    //插入数据库
					Db::name('kaoshi_jiankao')->insert($data);
				}
				else{
					Db::name('kaoshi_jiankao')
					    ->where('id', $jiankao['id'])
					    ->update($data);
				}
			}
	    }
	    return success();
	}
}

