<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_095701_alter_modems_change_primary_key extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_modems` DROP PRIMARY KEY;");
        $this->execute("ALTER TABLE `user_modems` ADD PRIMARY KEY(`serial_number`);");

    }

    public function down()
    {
        echo "m150608_095701_alter_modems_change_primary_key cannot be reverted.\n";

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
