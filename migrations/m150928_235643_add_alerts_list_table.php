<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_235643_add_alerts_list_table extends Migration
{
    public function up()
    {
        
        $this->createTable('alerts_list',[
            'id' => 'pk',
            'serial_number' => 'bigint(12)',
            'type' => "enum('leak','magnet','tamper')" ,
            'created_at' => 'timestamp',
            'status'=> "enum('ACTIVE','DEACTIVATED')" 
        ]);

    }

    public function down()
    {
        echo "m150928_235643_add_alerts_list_table cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
