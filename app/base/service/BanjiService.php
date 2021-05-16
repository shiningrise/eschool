<?php
namespace app\base\service;
use think\facade\Db;
use think\facade\Filesystem;

class BanjiService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,beizhu,is_graduated,kelei,bh,ji,xueduan,bzr_id,name,bianhao';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('banji')
            ->where($where)
            ->count('id');

        $list = Db::name('banji')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();
        foreach ($list as $k => $v) {
            $list[$k]['bzr_name'] = '';
            $bzr = TeacherService::info($v['bzr_id']);
            if ($bzr) {
                $list[$k]['bzr_name'] = $bzr['name'];
            }
        }

        $pages = ceil($count / $limit);

        $data['count'] = $count;
        $data['pages'] = $pages;
        $data['page']  = $page;
        $data['limit'] = $limit;
        $data['list']  = $list;

        return $data;
    }

    public static function info($id='')
    {
        $where[] = ['id', '=',  $id];
        $banji = Db::name('banji')
            ->where($where)
            ->find();
        $bzr = TeacherService::info($banji['bzr_id']);
        if ($bzr) {
            $banji['bzr_name'] = $bzr['name'];
        }
        return $banji;
    }

    public static function add($param)
    {
        $bzr_name = $param['bzr_name'];
        unset($param['bzr_name']);
        $teacher = TeacherService::getByName($bzr_name);
        if($teacher){
            $param['bzr_id']=$teacher['id'];
        }
        $id = Db::name('banji')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $bzr_name = $param['bzr_name'];
        unset($param['bzr_name']);
        $teacher = TeacherService::getByName($bzr_name);
        if($teacher){
            $param['bzr_id']=$teacher['id'];
        }
        $id = $param['id'];
        $res = Db::name('banji')
            ->where('id', $id)
            ->update($param);

        if (empty($res)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    public static function del($id)
    {
        Db::name('banji')->delete($id);
        return $id;
    }

}
