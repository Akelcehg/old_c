<?php

use yii\db\Schema;
use yii\db\Migration;

class m151018_160802_create_event_log_table extends Migration
{
    public function up()
    {
        
            $this->createTable('event_log',[
                'id' => 'pk',
                'user_id' => 'int(12)',
                'url'=>'text',
                'type' => 'enum("edit","correction","alert")',
                'description'=>'text',
                'created_at' => 'timestamp',      
            ]);

    }

    public function down()
    {
        echo "m151018_160802_create_event_log_table cannot be reverted.\n";

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
