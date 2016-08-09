<?php

use yii\db\Schema;
use yii\db\Migration;

class m160518_061655_metrix_modem_status_create extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('metrix_modem_status', true);
        if(!isset($table)) {
            $this->execute("
                              CREATE TABLE `metrix_modem_status` (
                              `id` int(10) unsigned NOT NULL,
                              `modem_id` int(11) DEFAULT NULL,
                              `balance` int(11) DEFAULT NULL,
                              `phone` varchar(255),
                              `signal_level` INT(3),
                              `invoice` text,
                              `time_on_line` timestamp NULL DEFAULT NULL,
                              `status` enum('Sleep','On-line','Busy','Disconnect') NOT NULL DEFAULT 'Sleep'
                            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
                            ");
        }


    }

    public function down()
    {
        echo "m160518_061655_metrix_modem_status_create cannot be reverted.\n";

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
