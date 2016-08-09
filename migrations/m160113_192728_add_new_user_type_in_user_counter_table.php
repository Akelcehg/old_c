<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_192728_add_new_user_type_in_user_counter_table extends Migration
{
    public function up()
    {

        $this->execute("ALTER TABLE `counters` MODIFY `user_type` ENUM('individual','legal_entity','house_metering');");

    }

    public function down()
    {
        echo "m160113_192728_add_new_user_type_in_user_counter_table cannot be reverted.\n";

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
