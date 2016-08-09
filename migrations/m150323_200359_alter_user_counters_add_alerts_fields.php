<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200359_alter_user_counters_add_alerts_fields extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_counters` ADD `leak` ENUM('0','1') AFTER `type`;");
        $this->execute("ALTER TABLE `user_counters` ADD `magnet` ENUM('0','1') AFTER `leak`;");
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
