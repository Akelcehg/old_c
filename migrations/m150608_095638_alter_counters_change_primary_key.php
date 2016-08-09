<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_095638_alter_counters_change_primary_key extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user_counters` DROP PRIMARY KEY ;");
        $this->execute("ALTER TABLE `user_counters` ADD PRIMARY KEY(`serial_number`);");

    }

    public function down()
    {
        echo "m150608_095638_alter_counters_change_primary_key cannot be reverted.\n";

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
