<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Permission extends Migrator
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
        $table  =  $this->table('permission',array('engine'=>'MyISAM'));
        $table->addColumn('permission_name', 'string',array('limit'  =>  32,'default'=>'','comment'=>'权限名称')) 
        ->addColumn('permission_code', 'string',array('limit'  =>  32,'default'=>'','comment'=>'权限代码')) 
        ->addColumn('xh', 'integer',array('limit'  =>  11,'comment'=>'序号'))
        ->addColumn('parent_id', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'父节点'))
        ->addColumn('remark', 'string',array('limit'  =>  32,'default'=>'','comment'=>'备注')) 
        ->create();

        $table  =  $this->table('role_permission',array('engine'=>'MyISAM'));
        $table->addColumn('role_id', 'integer',array('limit'  =>  11,'comment'=>'角色id'))
        ->addColumn('permission_id', 'integer',array('limit'  =>  11,'comment'=>'权限id'))
        ->addForeignKey('permission_id', 'permission', 'id', array('delete'=> 'CASCADE', 'update'=> 'CASCADE','constraint' => 'role_permission_permission_id'))
        ->addForeignKey('role_id', 'role', 'id', array('delete'=> 'CASCADE', 'update'=> 'CASCADE','constraint' => 'role_permission_role_id'))
        ->create();
    }
}
