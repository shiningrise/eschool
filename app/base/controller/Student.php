<?php
namespace app\base\controller;

use think\facade\Request;
use think\facade\Env;
use think\facade\Filesystem;
use think\facade\Config;
use think\facade\Db;
use app\BaseController;
use app\base\model\StudentModel;
use app\base\service\StudentService;
use app\base\service\BanjiService;
use app\base\validate\StudentValidate;
use hg\apidoc\annotation as Apidoc;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @Apidoc\Title("学生")
 * @Apidoc\Group("base")
 */
class Student extends BaseController
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
        $name               = Request::param('name/s', '');
        if ($name) {
            $where[] = ['name', 'LIKE','%' .$name. '%'];
        }
        $xh               = Request::param('xh/s', '');
        if ($xh) {
            $where[] = ['xh', 'LIKE','%' .$xh. '%'];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = StudentService::list($where, $page, $limit, $order);

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
        validate(StudentValidate::class)->scene('info')->check($param);
        $data = StudentService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("banji_name", type="string", default="", desc="班级名称")
     * @Apidoc\Param("beizhu2", type="string", default="", desc="备注2")
     * @Apidoc\Param("beizhu1", type="string", default="", desc="备注1")
     * @Apidoc\Param("beatbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("beatschool", type="string", default="", desc="在校")
     * @Apidoc\Param("idcardnum", type="string", default="", desc="身份证号")
     * @Apidoc\Param("sex", type="string", default="", desc="性别")
     * @Apidoc\Param("xh", type="string", default="", desc="学号")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("zzxh", type="string", default="", desc="中招序号")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['name'] 			= Request::param('name/s', '');
        $param['banji_name'] 			= Request::param('banji_name/s', '');
        $param['beizhu2'] 			= Request::param('beizhu2/s', '');
        $param['beizhu1'] 			= Request::param('beizhu1/s', '');
        $param['beatbook'] 			= Request::param('beatbook/s', '');
        $param['beatschool'] 			= Request::param('beatschool/s', '');
        $param['idcardnum'] 			= Request::param('idcardnum/s', '');
        $param['sex'] 			= Request::param('sex/s', '');
        $param['xh'] 			= Request::param('xh/s', '');
        $param['tel'] 			= Request::param('tel/s', '');
        $param['zzxh'] 			= Request::param('zzxh/s', '');
        validate(StudentValidate::class)->scene('add')->check($param);
        $data = StudentService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("banji_name", type="string", default="", desc="班级名称")
     * @Apidoc\Param("beizhu2", type="string", default="", desc="备注2")
     * @Apidoc\Param("beizhu1", type="string", default="", desc="备注1")
     * @Apidoc\Param("beatbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("beatschool", type="string", default="", desc="在校")
     * @Apidoc\Param("idcardnum", type="string", default="", desc="身份证号")
     * @Apidoc\Param("sex", type="string", default="", desc="性别")
     * @Apidoc\Param("xh", type="string", default="", desc="学号")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("zzxh", type="string", default="", desc="中招序号")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['banji_name']			= Request::param('banji_name/s', '');
        $param['beizhu2']			= Request::param('beizhu2/s', '');
        $param['beizhu1']			= Request::param('beizhu1/s', '');
        $param['beatbook']			= Request::param('beatbook/s', '');
        $param['beatschool']			= Request::param('beatschool/s', '');
        $param['idcardnum']			= Request::param('idcardnum/s', '');
        $param['sex']			= Request::param('sex/s', '');
        $param['xh']			= Request::param('xh/s', '');
        $param['tel']			= Request::param('tel/s', '');
        $param['zzxh']			= Request::param('zzxh/s', '');

        validate(StudentValidate::class)->scene('edit')->check($param);

        $data = StudentService::edit($param);

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

        validate(StudentValidate::class)->scene('del')->check($param);

        $data = StudentService::del($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("批量删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("ids", type="int[]", default="", desc="id数组")
     * @Apidoc\Returned(ref="return")
     */
    public function multiDelete()
    {
        //$param['ids']    = input("post.permission_ids/a");
        $param['ids']    = input();
        $data = StudentService::multiDelete($param['ids']);

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
        $savename = str_replace("\\","/",$savename);
        //$savename = app()->getRootPath() . 'public/' .'storage'.'/'.$savename;
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
        $data = [];

        for ($i = 2; $i <= $allRow; $i++)
        {
            $data = array();
            //学号	姓名	性别	班级
            $data['xh'] = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
            $data['name'] = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
            $data['sex'] = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
            $data['beatschool'] = true;
            $data['beatbook'] = true;
            $banji_name = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
            $banji = BanjiService::getByName($banji_name);
            if($banji){
                $data['banji_id']=$banji['id'];
            }else{
				//exception('找不到班级'.$banji_name);
				return error($data['xh'].$data['name'].'的班级'.$banji_name.'找不到');
			}
            //防止出现空白Excel导致mysql报错，对数据做下判断
            if(empty($data['xh']) && empty($data['name'])){
                //跳出循环
                break;
            }
            validate(StudentValidate::class)->scene('import')->check($data);
            //插入数据库
            Db::name('student')->insert($data);
        }
        return success();
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
        $filepath = Config::get('filesystem.disks.public.root').'/download/student.xlsx';
        return download($filepath , '学生导入模板.xlsx');
    }
}

