<?php
namespace app\base\controller;

use think\facade\Log;
use think\facade\Request;
use think\facade\Env;
use think\facade\Filesystem;
use think\facade\Config;
use think\facade\Db;
use app\BaseController;
use app\base\model\TeacherModel;
use app\base\service\TeacherService;
use app\base\validate\TeacherValidate;
use hg\apidoc\annotation as Apidoc;
use PhpOffice\PhpSpreadsheet\IOFactory;


/**
 * @Apidoc\Title("")
 * @Apidoc\Group("base")
 */
class Teacher extends BaseController
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

        $username   = Request::param('username/s', '');
        $name   = Request::param('name/s', '');
        $where = [];
        if ($username) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }
        if ($name) {
            $where[] = ['name', 'like', '%' . $name . '%'];
        }

        $order = [];
        if ($sort_field && $sort_type) {
            $order = [$sort_field => $sort_type];
        }
        $data = TeacherService::list($where, $page, $limit, $order);

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
        validate(TeacherValidate::class)->scene('info')->check($param);
        $data = TeacherService::info($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("添加")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("qy_userid", type="string", default="", desc="企业号ID")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("is_atbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("is_atschool", type="string", default="", desc="在校？")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("username", type="string", default="", desc="用户名")
     * @Apidoc\Returned(ref="return")
     */
    public function add()
    {
        $param['qy_userid'] 			= Request::param('qy_userid/s', '');
        $param['tel'] 			= Request::param('tel/s', '');
        $param['remark'] 			= Request::param('remark/s', '');
        $param['is_atbook'] 			= Request::param('is_atbook/s', '');
        $param['is_atschool'] 			= Request::param('is_atschool/s', '');
        $param['sort'] 			= Request::param('sort/s', '');
        $param['name'] 			= Request::param('name/s', '');
        $param['username'] 			= Request::param('username/s', '');
        validate(TeacherValidate::class)->scene('add')->check($param);
        $data = TeacherService::add($param);

        return success($data);
    }

    /**
     * @Apidoc\Title("修改")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("id", type="string", default="", desc="ID")
     * @Apidoc\Param("qy_userid", type="string", default="", desc="企业号ID")
     * @Apidoc\Param("tel", type="string", default="", desc="电话")
     * @Apidoc\Param("remark", type="string", default="", desc="备注")
     * @Apidoc\Param("is_atbook", type="string", default="", desc="在籍")
     * @Apidoc\Param("is_atschool", type="string", default="", desc="在校？")
     * @Apidoc\Param("sort", type="string", default="", desc="序号")
     * @Apidoc\Param("name", type="string", default="", desc="姓名")
     * @Apidoc\Param("username", type="string", default="", desc="用户名")
     * @Apidoc\Returned(ref="return")
     */
    public function edit()
    {
        $param['id']			= Request::param('id/s', '');
        $param['qy_userid']			= Request::param('qy_userid/s', '');
        $param['tel']			= Request::param('tel/s', '');
        $param['remark']			= Request::param('remark/s', '');
        $param['is_atbook']			= Request::param('is_atbook/s', '');
        $param['is_atschool']			= Request::param('is_atschool/s', '');
        $param['sort']			= Request::param('sort/s', '');
        $param['name']			= Request::param('name/s', '');
        $param['username']			= Request::param('username/s', '');

        validate(TeacherValidate::class)->scene('edit')->check($param);

        $data = TeacherService::edit($param);

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

        validate(TeacherValidate::class)->scene('del')->check($param);

        $data = TeacherService::del($param['id']);

        return success($data);
    }

    /**
     * @Apidoc\Title("批量删除")
     * @Apidoc\Method("POST")
     * @Apidoc\Header(ref="headerAdmin")
     * @Apidoc\Param("ids", type="int[]", default="1", desc="id数组")
     * @Apidoc\Returned(ref="return")
     */
    public function multiDelete()
    {
        //$param['ids']    = input("post.permission_ids/a");
        $param['ids']    = input();
        $data = TeacherService::multiDelete($param['ids']);

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
        $data = TeacherService::init($param['ids']);

        return success($data);
    }

    /**
     * @Apidoc\Title("删除")
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
            //序号	用户名	姓名	电话
            $data['sort'] = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
            $data['username'] = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
            $data['name'] = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
            $data['tel'] = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
            $data['is_atschool'] = true;
            $data['is_atbook'] = true;
            $data['qy_userid'] = $data['tel'];
            //防止出现空白Excel导致mysql报错，对数据做下判断
            if(empty($data['username']) && empty($data['name'])){
                //跳出循环
                break;
            }
            validate(TeacherValidate::class)->scene('import')->check($data);
            //插入数据库
            Db::name('teacher')->insert($data);
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
        $filepath = Config::get('filesystem.disks.public.root').'/download/teacher.xlsx';
        return download($filepath , '教师导入模板.xlsx');
    }
}

