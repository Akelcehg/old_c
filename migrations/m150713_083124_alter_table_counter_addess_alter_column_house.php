<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_083124_alter_table_counter_addess_alter_column_house extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `counter_address` CHANGE `house` `house` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
    }

    public function down()
    {
        echo "m150713_083124_alter_table_counter_addess_alter_column_house cannot be reverted.\n";

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
