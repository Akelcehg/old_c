<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_072922_add_indications_table extends Migration
{
    public function up()
    {

        $this->createTable('indications',[
            'id' => 'pk',
            'counter_id' => 'bigint(12) UNSIGNED NULL',
            'indications' => 'double(12,3)',
            'created_at' => 'timestamp',
        ]);

    }

    public function down()
    {
        echo "m151217_072922_add_indications_table cannot be reverted.\n";

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
