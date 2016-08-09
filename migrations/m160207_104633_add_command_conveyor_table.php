<?php

use yii\db\Schema;
use yii\db\Migration;

class m160207_104633_add_command_conveyor_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('command_conveyor', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `command_conveyor` (
                                        `id` int unsigned AUTO_INCREMENT,
                                        `modem_id` int,
                                        `command` varchar(255),
                                        `command_type` int,
                                        `status` enum('ACTIVE','DISABLED','PENDING') NOT NULL  DEFAULT'ACTIVE ',
                                        `created_at` timestamp NULL   DEFAULT CURRENT_TIMESTAMP,
                                        `pending_at` timestamp NULL,
                                        `disabled_at` timestamp NULL ,
                                         PRIMARY KEY (`id`));");
        }


    }

    public function down()
    {
        echo "m160207_104633_add_command_conveyor_table cannot be reverted.\n";

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
