<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200356_alter_user_add_geo_location_id extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `users` ADD `geo_location_id` varchar(12) AFTER `status`;");
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
