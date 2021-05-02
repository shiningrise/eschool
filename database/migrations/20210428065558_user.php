<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table  =  $this->table('user',array('engine'=>'MyISAM'));
        $table->addColumn('username', 'string',array('limit'  =>  15,'default'=>'','comment'=>'用户名，登陆使用'))
        ->addColumn('password', 'string',array('limit'  =>  32,'default'=>md5('123456'),'comment'=>'用户密码')) 
        ->addColumn('is_approved', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'是否审核'))
        ->addColumn('is_lockedout', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'是否锁定'))
        ->addColumn('last_lockout_date', 'datetime',array('default'=>'2021-04-28','comment'=>'最后锁定时间'))
        ->addColumn('failed_count', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'登陆失败次数'))
        ->addColumn('failed_start', 'datetime',array('default'=>'2021-04-28','comment'=>'登录失败开始时间'))
        ->addColumn('last_activity_date', 'datetime',array('default'=>'2021-04-28','comment'=>'最后活动时间'))
        ->addColumn('last_login_ip', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'最后登录IP'))
        ->addColumn('last_login_time', 'datetime',array('default'=>'2021-04-28','comment'=>'最后登录时间'))
        ->addColumn('is_delete', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'删除状态，1已删除'))
        ->addIndex(array('username'), array('unique'  =>  true))
        ->create();
    }
}
