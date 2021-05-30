<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Filesystem;

class Kaoshi_chengjiService{
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'c.id,xueke_id,x.name xueke_name,student_id,s.xh student_xh,s.name student_name,kaosheng_id,c.banji_id,b.name banji_name,kaoshi_id,fenshu_zhuguang,fenshu_keguang,mc_school,mc_banji,tscore,dengdi,fenshu';
        }

        if (empty($order)) {
            $order = ['s.xh' => 'asc'];
        }

        $count = Db::name('kaoshi_chengji')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi_chengji')
            ->alias('c')
            ->join('student s','s.id = c.student_id')
            ->join('banji b','c.banji_id = b.id')
            ->join('xueke x','c.xueke_id = x.id')
            ->field($field)
            ->where($where)
            ->page($page)
            ->limit($limit)
            ->order($order)
            ->select()
            ->toArray();

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
        $kaoshi_chengji = Db::name('kaoshi_chengji')
            ->where($where)
            ->find();
        return $kaoshi_chengji;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi_chengji')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi_chengji')
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
        Db::name('kaoshi_chengji')->delete($id);
        return $id;
    }

    //统计
    public static function tongji($kaoshi_id)
    {
        $xuekes = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
        $banjis = Kaoshi_kaoshengService::listKaoshiBanjis($kaoshi_id);
        foreach($xuekes as $xueke)
        {
            //求段名次
            $chengjis = Db::name('kaoshi_chengji')
                ->where('kaoshi_id',$kaoshi_id)
                ->where('xueke_id',$xueke['xueke_id'])
                ->order('fenshu','desc')
                ->select()
                ->toArray();
            $i=0;
            $lastFenshu = -1;
            $count = count($chengjis);
            for($i=0;$i<$count;$i++){
                if($chengjis[$i]['fenshu'] == $lastFenshu){
                    $chengjis[$i]['mc_school'] = $chengjis[$i-1]['mc_school'];
                    $lastFenshu=$chengjis[$i]['fenshu'];
                }
                else{
                    $chengjis[$i]['mc_school'] = $i+1;
                }
                Db::name('kaoshi_chengji')->where('id',$chengjis[$i]['id'])->update(['mc_school' => $chengjis[$i]['mc_school']]);
            }

            //求班名次
            foreach($banjis as $banji)
            {
                $chengjis = Db::name('kaoshi_chengji')
                        ->where('kaoshi_id',$kaoshi_id)
                        ->where('xueke_id',$xueke['xueke_id'])
                        ->where('banji_id',$banji['id'])
                        ->order('fenshu','desc')
                        ->select()
                        ->toArray();
                $i=0;
                $lastFenshu = -1;
                $count = count($chengjis);
                for($i=0;$i<$count;$i++){
                    if($chengjis[$i]['fenshu'] == $lastFenshu){
                        $chengjis[$i]['mc_school'] = $chengjis[$i-1]['mc_school'];
                        $lastFenshu=$chengjis[$i]['fenshu'];
                    }
                    else{
                        $chengjis[$i]['mc_banji'] = $i+1;
                    }
                    Db::name('kaoshi_chengji')->where('id',$chengjis[$i]['id'])->update(['mc_banji' => $chengjis[$i]['mc_banji']]);
                }
            }
        }
        return '';
    }
}
