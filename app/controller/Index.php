<?php
namespace app\controller;

use think\facade\Request;
use app\BaseController;
use app\model\User;
// 

class Index extends BaseController
{
    public function index()
    {
        // $user           = new User;
        // $user->username     = 'wxy';
        // $user->password    = md5('php@qq.com');
        // $user->save();
        
        // $user = User::where('Id',56)
        //  //   ->where('name','thinkphp')
        //     ->find();
        // $user->name     = 'thinkphp1';
        // $user->email    = 'thinkphp@qq.com';
        // $user->save();

        //$data = User::where('id','<',10000)->select();
		// 返回数据
		return success('hello');
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
