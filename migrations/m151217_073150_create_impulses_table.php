<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_073150_create_impulses_table extends Migration
{
    public function up()
    {

        $this->createTable('impulses',[
            'id' => 'pk',
            'indication_id' => 'int(12)',
            'impulse' => 'int(12)',
            'created_at' => 'timestamp',
        ]);

    }

    public function down()
    {
        echo "m151217_073150_create_impulses_table cannot be reverted.\n";

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
