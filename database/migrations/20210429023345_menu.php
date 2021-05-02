<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Menu extends Migrator
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
        $table  =  $this->table('menu',array('engine'=>'MyISAM'));
        $table->addColumn('menu_name', 'string',array('limit'  =>  15,'default'=>'','comment'=>'菜单名称'))
        ->addColumn('menu_url', 'string',array('limit'  =>  15,'default'=>'','comment'=>'菜单URL'))
        ->addColumn('menu_icon', 'string',array('limit'  =>  15,'default'=>'','comment'=>'菜单图标'))
        ->addColumn('permission_code', 'string',array('limit'  =>  15,'default'=>'','comment'=>'权限代码'))
        ->addColumn('xh', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'菜单序号'))
        ->addColumn('parent_id', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'父菜单ID'))
        ->create();
    }
}
