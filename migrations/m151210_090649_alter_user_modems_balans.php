<?php

use yii\db\Schema;
use yii\db\Migration;

class m151210_090649_alter_user_modems_balans extends Migration
{
    public function up()
    {

        $this->execute("ALTER TABLE `user_modems` ADD `balans` DOUBLE (8,5) AFTER `created_at`;");

    }

    public function down()
    {
        echo "m151210_090649_alter_user_modems_balans cannot be reverted.\n";

        return false;
    }
}
