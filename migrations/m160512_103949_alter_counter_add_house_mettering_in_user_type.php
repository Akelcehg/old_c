<?php

use yii\db\Schema;
use yii\db\Migration;

class m160512_103949_alter_counter_add_house_mettering_in_user_type extends Migration
{
    public function up()
    {

        $this->execute("ALTER TABLE `counters` CHANGE `user_type` `user_type` enum('individual', 'legal_entity', 'house_metering')");

    }

    public function down()
    {
        echo "m160512_103949_alter_counter_add_house_mettering_in_user_type cannot be reverted.\n";

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
