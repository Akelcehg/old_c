<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200352_counter_address extends Migration
{
    public function up()
    {
        $this->execute("
          CREATE TABLE IF NOT EXISTS `counter_address` (
          `id` int(11) NOT NULL,
          `region_id` int(11) NOT NULL,
          `address` text NOT NULL,
          `longitude` varchar(25) DEFAULT NULL,
          `latitude` varchar(25) DEFAULT NULL
        ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
      ");

        $this->createIndex("region_id", "counter_address", "region_id");


    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
