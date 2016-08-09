<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_143121_create_alerts_to_user_config extends Migration
{
    public function up()
    {
        
         $this->createTable('alerts_to_user',[
            'id' => 'pk',
            'user_id' => 'int(12)',
            'alerts_type_id' => 'int(12)',
        ]);

    }

    public function down()
    {
        echo "m151001_143121_create_alerts_to_user_config cannot be reverted.\n";

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
