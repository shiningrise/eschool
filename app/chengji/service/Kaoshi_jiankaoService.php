<?php
namespace app\chengji\service;
use think\facade\Db;
use think\facade\Log;
use think\facade\Filesystem;
use app\base\service\TeacherService;

class Kaoshi_jiankaoService{
	public static function save($kaoshi_id,$data){
		foreach($data as $item){
			$jiankao = Db::name('kaoshi_jiankao')
				->where('kaoshi_id',$item['kaoshi_id'])
				->where('shichang_id',$item['shichang_id'])
				->where('xueke_id',$item['xueke_id'])
				->find();
			if($jiankao == null){
				$param['kaoshi_id'] = $item['kaoshi_id'];
				$param['shichang_id'] = $item['shichang_id'];
				$param['xueke_id'] = $item['xueke_id'];
				$param['teacher_id'] = $item['teacher_id'];
				self::add($param);
			}else{
				Db::name('kaoshi_jiankao')
				    ->where('id', $jiankao['id'])
				    ->update(['teacher_id' => $item['teacher_id']]);
			}
		}
		$jiankaoData = self::getByKaoshiId($kaoshi_id);
		foreach($jiankaoData as $jiankao){
			$found = false;
			foreach($data as $item){
				if($item['kaoshi_id'] == $jiankao['kaoshi_id'] && $item['shichang_id'] == $jiankao['shichang_id'] && $item['xueke_id']==$jiankao['xueke_id']){
					$found = true;
					break;
				}
			}
			if($found == false){
				Db::name('kaoshi_jiankao')->delete($jiankao['id']);
			}
		}
		return '';
	}
	
	public static function getJiaokaoTable($kaoshi_id){
		$data['shichang'] = Kaoshi_shichangService::getByKaoshiId($kaoshi_id);
		$data['xueke'] = Kaoshi_xuekeService::getByKaoshiId($kaoshi_id);
		$data['teacher'] = TeacherService::getList();
		$data['jiankao'] = self::getByKaoshiId($kaoshi_id);
		return $data;
	}
	public static function getByKaoshiId($kaoshi_id){
		$where[] = ['kaoshi_id','=',$kaoshi_id];
		$field = 'id,teacher_id,shichang_id,xueke_id,kaoshi_id';
        $data = Db::name('kaoshi_jiankao')
            ->field($field)
            ->where($where)
            ->select()
            ->toArray();
		return $data;
	}
    public static function list($where = [], $page = 1, $limit = 10,  $order = [], $field = '')
    {
        if (empty($field)) {
            $field = 'id,teacher_id,shichang_id,xueke_id,kaoshi_id';
        }

        if (empty($order)) {
            $order = ['id' => 'desc'];
        }

        $count = Db::name('kaoshi_jiankao')
            ->where($where)
            ->count('id');

        $list = Db::name('kaoshi_jiankao')
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
        $kaoshi_jiankao = Db::name('kaoshi_jiankao')
            ->where($where)
            ->find();
        return $kaoshi_jiankao;
    }

    public static function add($param)
    {
        $id = Db::name('kaoshi_jiankao')->insertGetId($param);

        if (empty($id)) {
            exception();
        }

        $param['id'] = $id;

        return $param;
    }

    
    public static function edit($param)
    {
        $id = $param['id'];
        $res = Db::name('kaoshi_jiankao')
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
        Db::name('kaoshi_jiankao')->delete($id);
        return $id;
    }

}
