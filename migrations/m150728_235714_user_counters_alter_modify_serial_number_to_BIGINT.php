<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_235714_user_counters_alter_modify_serial_number_to_BIGINT extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_counters` CHANGE `serial_number` `serial_number` BIGINT(12) ");

    }

    public function down()
    {
        echo "m150728_235714_user_counters_alter_modify_serial_number_to_BIGINT cannot be reverted.\n";

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
