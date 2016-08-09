<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_113639_number_format_fix extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_indications` MODIFY `indications`  DOUBLE(12,3) ;");
        $this->execute("ALTER TABLE `user_counters` MODIFY `initial_indications`  DOUBLE(12,3) ;");
        $this->execute("ALTER TABLE `user_counters` MODIFY `last_indications`  DOUBLE(12,3) ;");

    }

    public function down()
    {
        echo "m151021_113639_number_format_fix cannot be reverted.\n";

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
