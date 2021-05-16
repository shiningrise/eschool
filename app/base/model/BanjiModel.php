<?php
declare (strict_types = 1);

namespace app\base\model;

use think\Model;

class BanjiModel extends Model
{
    protected $name = 'banji';
    protected $schema = [

    ];

    public function bzr()
    {
        return $this->belongsTo(Banji::class);
    }
}
