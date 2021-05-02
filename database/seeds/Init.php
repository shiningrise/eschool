<?php

use think\migration\Seeder;

class Init extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $rows = [];        
        for ($i = 0; $i < 10; $i++) {
            $rows[] = ['username' => 'wxy'.$i,                            
                'password' => md5('123456'),
            ];
        }        
        $this->table('user')->insert($rows)->save();
    }
}