<?php
declare (strict_types = 1);

namespace app\base\model;

use think\Model;

class StudentModel extends Model
{
    protected $name = 'student';
    protected $schema = [

    ];

    public function banji()
    {
        return $this->belongsTo(Banji::class);
    }
}
