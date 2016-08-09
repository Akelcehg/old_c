<?php

use yii\db\Schema;
use yii\db\Migration;

class m151017_233827_create_correction_table extends Migration
{
    public function up()
    {
        
        $this->createTable('correction',[
            'id' => 'pk',
            'user_counter_id' => 'int(12)',
            'old_indications_id' => 'int(12)',
            'new_indications_id' => 'int(12)',
            'description'=>'text'
        ]);

    }

    public function down()
    {
        echo "m151017_233827_create_correction_table cannot be reverted.\n";

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
