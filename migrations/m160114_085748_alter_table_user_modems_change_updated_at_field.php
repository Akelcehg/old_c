<?php

use yii\db\Schema;
use yii\db\Migration;

class m160114_085748_alter_table_user_modems_change_updated_at_field extends Migration
{
    public function up()
    {

        $this->execute("ALTER TABLE `user_modems` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00';");

    }

    public function down()
    {
        echo "m160114_085748_alter_table_user_modems_change_updated_at_field cannot be reverted.\n";

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
