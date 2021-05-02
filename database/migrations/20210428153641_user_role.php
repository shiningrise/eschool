<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UserRole extends Migrator
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
        $table  =  $this->table('user_role',array('engine'=>'MyISAM'));
        $table->addColumn('user_id', 'integer',array('limit'  =>  11,'comment'=>'用户id'))
              ->addColumn('role_id', 'integer',array('limit'  =>  11,'comment'=>'角色id'))
              ->addForeignKey('user_id', 'user', 'id', array('delete'=> 'CASCADE', 'update'=> 'CASCADE','constraint' => 'user_role_userid'))
              ->addForeignKey('role_id', 'role', 'id', array('delete'=> 'CASCADE', 'update'=> 'CASCADE','constraint' => 'user_role_roleid'))
              ->create();
    }
}
