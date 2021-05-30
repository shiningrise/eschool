<?php
namespace app\base\controller;

use think\facade\Log;
use think\facade\Db;
use think\facade\Env;
use think\facade\Filesystem;
use think\facade\Config;
use app\BaseController;
use app\base\model\ShoukebiaoModel;
use app\base\service\ShoukebiaoService;
use app\base\service\XuekeService;
use app\base\validate\ShoukebiaoValidate;
use app\base\service\BanjiService;
use app\base\service\TeacherService;
use hg\apidoc\annotation as Apidoc;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @Apidoc\Title("授课表")
 * @Apidoc\Group("base")
 */
class Shoukebiao extends BaseController
{
    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Returned(ref="return")
     */
    public function save()
    {
        $shoukebiaos = input();
        foreach($shoukebiaos as $shoukebiao)
        {
            $where = [];
            $where[] = ['banji_id', '=',$shoukebiao['banji_id']];
            $where[] = ['xueke_id', '=',$shoukebiao['xueke_id']];
            //$where[] = ['teacher_id', '=',$shoukebiao['teacher_id']];
            $shoukebiaoOld = Db::name('shoukebiao')->where($where)->find();
            if(!$shoukebiaoOld){
                validate(ShoukebiaoValidate::class)->scene('add')->check($shoukebiao);
                $data = ShoukebiaoService::add($shoukebiao);
            }
            else{
                $shoukebiaoOld['teacher_id']=$shoukebiao['teacher_id'];
                validate(ShoukebiaoValidate::class)->scene('edit')->check($shoukebiaoOld);
                $data = ShoukebiaoService::edit($shoukebiaoOld);
            }
        }
        $list = ShoukebiaoService::getActiveList();
        foreach($list as $item){
            $found = false;
            foreach($shoukebiaos as $shoukebiao)
            {
                if($shoukebiao['banji_id']==$item['banji_id'] && $shoukebiao['xueke_id']==$item['xueke_id'] && $shoukebiao['teacher_id']==$item['teacher_id'])
                {
                    $found = true;
                    break;
                }
            }
            if($found==false){
                ShoukebiaoService::del($item['id']);
            }
        }
        // 'banji_id' => '83',
        // 'xueke_id' => '2',
        // 'teacher_id' => 1095,
        return success();
    }
    /**
     * @Apidoc\Title("授课表信息")
     * @Apidoc\Returned(ref="return")
     */
    public function getTable()
    {
        $data = ShoukebiaoService::getTable();

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
        $data = ShoukebiaoService::list($where, $page, $limit, $order);

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
        validate(ShoukebiaoValidate::class)->scene('info')->check($param);
        $data = ShoukebiaoService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['teacher_id'] 			= Request::param('teacher_id/s', '');
        $param['xueke_id'] 			= Request::param('xueke_id/s', '');
        $param['banji_id'] 			= Request::param('banji_id/s', '');
        validate(ShoukebiaoValidate::class)->scene('add')->check($param);
        $data = ShoukebiaoService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("teacher_id", type="string", default="", desc="教师")
     * @Apidoc\Param("xueke_id", type="string", default="", desc="学科")
     * @Apidoc\Param("banji_id", type="string", default="", desc="班级")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['teacher_id']			= Request::param('teacher_id/s', '');
        $param['xueke_id']			= Request::param('xueke_id/s', '');
        $param['banji_id']			= Request::param('banji_id/s', '');

        validate(ShoukebiaoValidate::class)->scene('edit')->check($param);

        $data = ShoukebiaoService::edit($param);

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

        validate(ShoukebiaoValidate::class)->scene('del')->check($param);

        $data = ShoukebiaoService::del($param['id']);

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
	    $filepath = Config::get('filesystem.disks.public.root').'/download/授课表.xlsx';
	    return download($filepath , '授课表.xlsx');
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
		for ($i='B';$i<=$allCol;$i++){
			$xueke[] = $worksheet->getCell($i.'1')->getValue();
		}
		$xuekeList = XuekeService::getActiveXuekes();
		$xuekeArray = [];
		foreach($xuekeList as $xuekeItem){
			$xuekeArray[$xuekeItem['name']]=$xuekeItem['id'];
		}
	    for ($i = 2; $i <= $allRow; $i++)
	    {
	        $data = [];
	        $banji_name = $worksheet->getCell('A'.$i)->getValue();
            $banji = BanjiService::getByName($banji_name);
            if(empty($banji)){
                continue;
            }
            $data['banji_id'] = $banji['id'];
			$colIndex = 0;
			for ($j='B';$j <= $allCol;$j++){
				$xuekeValue = $xueke[$colIndex];
				$colIndex++;
				if(array_key_exists($xuekeValue,$xuekeArray))
				{
					$data['xueke_id']=$xuekeArray[$xuekeValue];
				}else{
					continue;
				}
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
				
				$shoukebiao = Db::name('shoukebiao')
                                ->where('banji_id',$data['banji_id'])
								->where('xueke_id',$data['xueke_id'])
								->find();
				if($shoukebiao == null){
					Db::name('shoukebiao')->insert($data);
				}
				else{
					Db::name('shoukebiao')
					    ->where('id', $shoukebiao['id'])
					    ->update($data);
				}
			}
	    }
	    return success();
	}
}

