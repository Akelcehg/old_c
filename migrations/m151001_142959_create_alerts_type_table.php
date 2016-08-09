<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_142959_create_alerts_type_table extends Migration
{
    public function up()
    {
        
        $this->createTable('alerts_types',[
            'id' => 'pk',
            'name' => 'varchar(255)',
        ]);

    }

    public function down()
    {
        echo "m151001_142959_create_alerts_type_table cannot be reverted.\n";

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
