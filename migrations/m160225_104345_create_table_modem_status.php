<?php

use yii\db\Schema;
use yii\db\Migration;

class m160225_104345_create_table_modem_status extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('modem_status', true);
        if(!isset($table)) {
            $this->execute("
                              CREATE TABLE `modem_status` (
                              `id` int(10) unsigned NOT NULL,
                              `modem_id` int(11) DEFAULT NULL,
                              `balance` int(11) DEFAULT NULL,
                              `phone` varchar(255),
                              `invoice` text,
                              `time_on_line` timestamp NULL DEFAULT NULL,
                              `status` enum('Sleep','On-line','Busy','Disconnect') NOT NULL DEFAULT 'Sleep'
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                            ");
        }

    }

    public function down()
    {
        echo "m160225_104345_create_table_modem_status cannot be reverted.\n";

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
