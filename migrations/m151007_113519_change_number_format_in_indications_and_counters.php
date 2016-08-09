<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_113519_change_number_format_in_indications_and_counters extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_indications` MODIFY `indications`  DOUBLE(8,3) ;");
        
        $this->execute("ALTER TABLE `user_counters` MODIFY `initial_indications`  DOUBLE(8,3) ;");
        $this->execute("ALTER TABLE `user_counters` MODIFY `last_indications`  DOUBLE(8,3) ;");
    }

    public function down()
    {
        echo "m151007_113519_change_number_format_in_indications_and_counters cannot be reverted.\n";

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
