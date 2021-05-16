<?php
declare (strict_types = 1);

namespace app\base\model;

use think\Model;

class TeacherModel extends Model
{
    protected $name = 'teacher';
    protected $schema = [

    ];

    public function banjis()
    {
        return $this->hasMany(Banji::class,'bzr_id');
    }
}
