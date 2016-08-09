<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200358_alter_user_counters_add_type_counter extends Migration
{
    public function up()
    {
        
        $this->execute("ALTER TABLE `user_counters` ADD `where_installed` ENUM('home','flat') AFTER `geo_location_id`;");
        $this->execute("ALTER TABLE `user_counters` ADD `type` ENUM('gas','water') AFTER `where_installed`;");
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
