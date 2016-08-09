<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200355_alter_user_counters_add_last_indications extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user_counters` ADD `last_indications` int(12) AFTER `initial_indications`;");
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
