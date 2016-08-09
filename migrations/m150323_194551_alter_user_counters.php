<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_194551_alter_user_counters extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user_counters` CHANGE `serial_number` `serial_number` INT(10) NULL DEFAULT NULL;");


        $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        if(isset($table->columns['update_interval'])) {
            $this->dropColumn("user_counters", "update_interval");
        }

        if(!isset($table->columns['tamper_detect'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `tamper_detect` TIMESTAMP NULL DEFAULT NULL AFTER battery_level;");
        }

        if(!isset($table->columns['tamper_detect_key'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `tamper_detect_key` tinyint(3) UNSIGNED NULL DEFAULT NULL AFTER tamper_detect;");
        }

        if(!isset($table->columns['account'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `account` varchar(255) NULL DEFAULT NULL AFTER created_at;");
        }

        if(!isset($table->columns['flat'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `flat` int(10) UNSIGNED NULL DEFAULT NULL AFTER account;");
        }

        if(!isset($table->columns['geo_location_id'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `geo_location_id` int(12) UNSIGNED NULL DEFAULT NULL AFTER flat;");
        }

        /**
         * Make all unsigned
         */

        $this->execute("ALTER TABLE `user_counters` CHANGE `user_id` `user_id` INT(12) UNSIGNED NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `user_counters` CHANGE `user_modem_id` `user_modem_id` INT(12) UNSIGNED NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `user_counters` CHANGE `serial_number` `serial_number` INT(10) UNSIGNED NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `user_counters` CHANGE `battery_level` `battery_level` TINYINT(5) UNSIGNED NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `user_counters` CHANGE `tamper_detect_key` `tamper_detect_key` TINYINT(3) UNSIGNED NULL DEFAULT NULL;");


        $this->createIndex('user_id', 'user_counters', 'user_id');
        $this->createIndex('user_modem_id', 'user_counters', 'user_modem_id');
        $this->createIndex('serial_number', 'user_counters', 'serial_number');
        $this->createIndex('battery_level', 'user_counters', 'battery_level');


    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
