<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200357_alter_counter_adress_add_coordinates extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('counter_address', true);

        if(!isset($table->columns['longitude'])) {
            $this->execute("ALTER TABLE `counter_address` ADD `longitude` varchar(25) AFTER `address`;");
        }

        if(!isset($table->columns['latitude'])) {
            $this->execute("ALTER TABLE `counter_address` ADD `latitude` varchar(25) AFTER `longitude`;");
        }
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
