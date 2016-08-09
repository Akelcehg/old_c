<?php

use yii\db\Schema;
use yii\db\Migration;

class m151019_161140_modify_user_modems_serial_number_to_int extends Migration
{
    public function up()
    {
            $this->execute("ALTER TABLE `user_modems` MODIFY `serial_number`  int(12) ;");
    }

    public function down()
    {
        echo "m151019_161140_modify_user_modems_serial_number_to_int cannot be reverted.\n";

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
