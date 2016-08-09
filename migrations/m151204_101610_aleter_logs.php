<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_101610_aleter_logs extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `event_log` ADD `region_id` INT (10) AFTER `type`;");
        $this->execute("ALTER TABLE `event_log` ADD `counter_type` ENUM('gas','water') AFTER `region_id`;");
    }

    public function down()
    {
        echo "m151204_101610_aleter_logs cannot be reverted.\n";

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
