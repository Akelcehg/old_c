<?php

use yii\db\Schema;
use yii\db\Migration;

class m150728_233200_user_alter_modify_role_to_text extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `users` CHANGE `role` `role` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    public function down()
    {
        echo "m150728_233200_user_alter_modify_role_to_text cannot be reverted.\n";

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
