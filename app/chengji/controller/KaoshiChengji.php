<?php
namespace app\chengji\controller;

use think\facade\Request;
use think\facade\Db;
use think\facade\Env;
use think\facade\Filesystem;
use think\facade\Config;
use think\facade\Log;
use app\BaseController;
use app\chengji\model\Kaoshi_chengjiModel;
use app\chengji\service\Kaoshi_chengjiService;
use app\chengji\service\Kaoshi_xuekeService;
use app\chengji\service\Kaoshi_kaoshengService;
use app\chengji\service\KaoshiService;
use app\base\service\StudentService;
use app\chengji\validate\Kaoshi_chengjiValidate;
use hg\apidoc\annotation as Apidoc;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * @Apidoc\Title("考试成绩")
 * @Apidoc\Group("chengji")
 */
class KaoshiChengji extends BaseController
{
	
	/**
	 * @Apidoc\Title("列出我的成绩汇总")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function listMyChengjiHuizong()
	{
		$user_id = user_id();
		$student = StudentService::getByUsrId($user_id);
	    $data = Kaoshi_chengjiService::listMyChengjiHuizong($student['id']);
	    return success($data);
	}
	
	/**
	 * @Apidoc\Title("列出成绩汇总")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="kaoshi_id")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function listChengjiHuizong()
	{
		$kaoshi_id       = Request::param('kaoshi_id/d', 0);
		$banji_id       = Request::param('banji_id/d', 0);
	    $data = Kaoshi_chengjiService::listChengjiHuizong($kaoshi_id,$banji_id);
	    return success($data);
	}
	
	/**
	 * @Apidoc\Title("列出考试班级")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="kaoshi_id")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function listKaoshiXuekes()
	{
		$kaoshi_id       = Request::param('kaoshi_id/d', 1);
	    $data = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
	    return success($data);
	}
	
	/**
	 * @Apidoc\Title("列出考试班级")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="kaoshi_id")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function listBanjisByKaoshiId()
	{
		$kaoshi_id       = Request::param('kaoshi_id/d', 1);
	    $data = Kaoshi_kaoshengService::listKaoshiBanjis($kaoshi_id);
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

		$kaoshi_id       = Request::param('kaoshi_id/d',0);
		$banji_id       = Request::param('banji_id/d', 0);
		$xueke_id       = Request::param('xueke_id/d', 0);
		
        $where = [];
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		if($banji_id)
			$where[] = ['kaoshi_chengji.banji_id','=',$banji_id];
		if($xueke_id)
			$where[] = ['kaoshi_chengji.xueke_id','=',$xueke_id];
        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = Kaoshi_chengjiService::list($where, $page, $limit, $order);

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
        validate(Kaoshi_chengjiValidate::class)->scene('info')->check($param);
        $data = Kaoshi_chengjiService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("student_id", type="string", default="", desc="学生")
     * @Apidoc\Param("kaosheng_id", type="string", default="", desc="考生")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("fenshu_zhuguang", type="string", default="", desc="主观题分数")
     * @Apidoc\Param("fenshu_keguang", type="string", default="", desc="客观题分数")
     * @Apidoc\Param("mc_school", type="string", default="", desc="校内名次")
     * @Apidoc\Param("mc_banji", type="string", default="", desc="班内名次")
     * @Apidoc\Param("tscore", type="string", default="", desc="标准分")
     * @Apidoc\Param("dengdi", type="string", default="", desc="等第")
     * @Apidoc\Param("fenshu", type="string", default="", desc="分数")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['student_id'] 			= Request::param('student_id/s', '');
        $param['kaosheng_id'] 			= Request::param('kaosheng_id/s', '');
        $param['banji_id'] 			= Request::param('banji_id/s', '');
        $param['kaoshi_id'] 			= Request::param('kaoshi_id/s', '');
        $param['fenshu_zhuguang'] 			= Request::param('fenshu_zhuguang/s', '');
        $param['fenshu_keguang'] 			= Request::param('fenshu_keguang/s', '');
        $param['mc_school'] 			= Request::param('mc_school/s', '');
        $param['mc_banji'] 			= Request::param('mc_banji/s', '');
        $param['tscore'] 			= Request::param('tscore/s', '');
        $param['dengdi'] 			= Request::param('dengdi/s', '');
        $param['fenshu'] 			= Request::param('fenshu/s', '');
        validate(Kaoshi_chengjiValidate::class)->scene('add')->check($param);
        $data = Kaoshi_chengjiService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("student_id", type="string", default="", desc="学生")
     * @Apidoc\Param("kaosheng_id", type="string", default="", desc="考生")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Param("kaoshi_id", type="string", default="", desc="考试")
     * @Apidoc\Param("fenshu_zhuguang", type="string", default="", desc="主观题分数")
     * @Apidoc\Param("fenshu_keguang", type="string", default="", desc="客观题分数")
     * @Apidoc\Param("mc_school", type="string", default="", desc="校内名次")
     * @Apidoc\Param("mc_banji", type="string", default="", desc="班内名次")
     * @Apidoc\Param("tscore", type="string", default="", desc="标准分")
     * @Apidoc\Param("dengdi", type="string", default="", desc="等第")
     * @Apidoc\Param("fenshu", type="string", default="", desc="分数")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['student_id']			= Request::param('student_id/s', '');
        $param['kaosheng_id']			= Request::param('kaosheng_id/s', '');
        $param['banji_id']			= Request::param('banji_id/s', '');
        $param['kaoshi_id']			= Request::param('kaoshi_id/s', '');
        $param['fenshu_zhuguang']			= Request::param('fenshu_zhuguang/s', '');
        $param['fenshu_keguang']			= Request::param('fenshu_keguang/s', '');
        $param['mc_school']			= Request::param('mc_school/s', '');
        $param['mc_banji']			= Request::param('mc_banji/s', '');
        $param['tscore']			= Request::param('tscore/s', '');
        $param['dengdi']			= Request::param('dengdi/s', '');
        $param['fenshu']			= Request::param('fenshu/s', '');

        validate(Kaoshi_chengjiValidate::class)->scene('edit')->check($param);

        $data = Kaoshi_chengjiService::edit($param);

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

        validate(Kaoshi_chengjiValidate::class)->scene('del')->check($param);

        $data = Kaoshi_chengjiService::del($param['id']);

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
        $filepath = Config::get('filesystem.disks.public.root').'/download/成绩导入模板.xlsx';
        return download($filepath , '成绩导入模板.xlsx');
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
		for ($i='E';$i<=$allCol;$i++){
			$xueke[] = $worksheet->getCell($i.'1')->getValue();
		}
		$xuekeList = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
		$xuekeArray = [];
		foreach($xuekeList as $xuekeItem){
			$xuekeArray[$xuekeItem['xueke_name']]=$xuekeItem['xueke_id'];
		}
	    for ($i = 2; $i <= $allRow; $i++)
	    {
            $xh = $worksheet->getCell('C'.$i)->getValue();
		    //防止出现空白Excel导致mysql报错，对数据做下判断
            if(empty($xh)){
                //跳出循环
                break;
            }
            $stu = StudentService::getByXh($xh);
            if(empty($stu))
                continue;
	        $data = [];
            $data['student_id'] = $stu['id'];
            $data['banji_id'] = $stu['banji_id'];
	        $num = $worksheet->getCell('A'.$i)->getValue();
			$cloIndex = 0;
			for ($j='E';$j <= $allCol;$j++){
				$xuekeValue = $xueke[$cloIndex];
				$cloIndex++;
				if(array_key_exists($xuekeValue,$xuekeArray))
				{
					$data['xueke_id']=$xuekeArray[$xuekeValue];
				}else{
					continue;
				}
				$data['kaoshi_id'] = $kaoshi_id;
				$data['fenshu'] = $worksheet->getCell($j.$i)->getValue();

                $kaosheng = Db::name('kaoshi_kaosheng')
                                        ->where('kaoshi_id',$data['kaoshi_id'])
										->where('student_id',$data['student_id'])
										->find();
                $data['kaosheng_id'] = $kaosheng['id'];
				$chengji = Db::name('kaoshi_chengji')->where('kaoshi_id',$data['kaoshi_id'])
										->where('xueke_id',$data['xueke_id'])
										->where('student_id',$data['student_id'])
										->find();
				if($chengji == null){
					Db::name('kaoshi_chengji')->insert($data);
				}
				else{
					Db::name('kaoshi_chengji')->where('id', $chengji['id'])->update($data);
				}
			}
	    }
	    return success();
	}

    /**
     * @Apidoc\Title("统计各科名次与标准分")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
     * @Apidoc\Returned(ref="return")
     */
    public function tongji()
    {
        $param['kaoshi_id'] = Request::param('kaoshi_id/d', '');

        Kaoshi_chengjiService::tongji($param['kaoshi_id']);

        return success();
    }
	
	/**
	 * @Apidoc\Title("统计总分名次")
	 * @Apidoc\Method("POST")
	 * @Apidoc\Header(ref="headerAdmin")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Returned(ref="return")
	 */
	public function tongjiZongfen()
	{
	    $param['kaoshi_id'] = Request::param('kaoshi_id/d', '');
	
	    Kaoshi_chengjiService::tongjiZongfen($param['kaoshi_id']);
	
	    return success();
	}
	
	/**
	 * @Apidoc\Title("批量删除考生")
	 * @Apidoc\Param("ids", type="int[]", default="[]", desc="考生ID数组")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function multiDelete(){
		$ids   	= input("post.ids/a");
		foreach($ids as $id){
			Kaoshi_chengjiService::del($id);
		}
		return success();
	}
	
	/**
	 * @Apidoc\Title("导出成绩")
	 * @Apidoc\Param("kaoshi_id", type="int", default="0", desc="考试ID")
	 * @Apidoc\Returned(ref="return"),
	 * @Apidoc\Returned("data", type="object", desc="返回数据")
	 */
	public function outExcel(){
		$kaoshi_id       = Request::param('kaoshi_id/d', 0);
		$banji_id       = Request::param('banji_id/d', 0);
		
		$kaoshi = KaoshiService::info($kaoshi_id);
		$xuekeList = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
		$kaoshengs = Kaoshi_kaoshengService::listByKaoshiId($kaoshi_id);
		
		$newExcel = new Spreadsheet();  //创建一个新的excel文档
		$objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
		$objSheet->setTitle($kaoshi['name'].'成绩汇总');  //设置当前sheet的标题
	 
		$newExcel->getActiveSheet()->getColumnDimension('A')->setWidth('10');
		$newExcel->getActiveSheet()->getColumnDimension('B')->setWidth('10');
		$newExcel->getActiveSheet()->getColumnDimension('C')->setWidth('10');
		$count = count($xuekeList);
		
		for ($i='D',$j=0;$j<$count;$i++,$j++){
			$newExcel->getActiveSheet()->getColumnDimension($i)->setWidth('10');
		}
		
		$objSheet->setCellValue('A1', '学号')->setCellValue('B1', '姓名')->setCellValue('C1', '班级');
		for ($i='D',$j=0;$j<$count;$i++,$j++){
			$objSheet->setCellValue($i.'1',$xuekeList[$j]['xueke_name']);
		}
		
		$data = Kaoshi_chengjiService::listChengjiHuizong($kaoshi_id,$banji_id);
		$kaoshengcount = count($data['kaoshengs']);
		for ($i = 2; $i <= $kaoshengcount+1; $i++) {	
			$objSheet->setCellValue('A' . $i, $data['kaoshengs'][$i-2]['student_xh']);
			$objSheet->setCellValue('B' . $i, $data['kaoshengs'][$i-2]['student_name']);
			$objSheet->setCellValue('C' . $i, $data['kaoshengs'][$i-2]['banji_name']);
			$ii='D';
			$j=0;		
			for (;$j<$count;$ii++,$j++){
				if(isset($data['kaoshengs'][$i-2]['chengji'][$xuekeList[$j]['xueke_id']]))
					$objSheet->setCellValue($ii.$i,$data['kaoshengs'][$i-2]['chengji'][$xuekeList[$j]['xueke_id']]);
			}
			// $objSheet->setCellValue($ii.$i,$data['kaoshengs'][$i-2]['zongfen']);
			// $ii++;
			// $objSheet->setCellValue($ii.$i,$data['kaoshengs'][$i-2]['mc_banji']);
			// $ii++;
			// $objSheet->setCellValue($ii.$i,$data['kaoshengs'][$i-2]['mc_school']);
			
			// $objSheet->getCell('E' . $i)->setValueExplicit($kaoshengs[$i-2]['shichangnum'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		}
		
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=".$kaoshi['name'].'成绩汇总'.".xls");
		header('Cache-Control: max-age=0');
		$objWriter = IOFactory::createWriter($newExcel, 'Xls');
		$objWriter->save('php://output');
		
		return success();
	}
}

