<?php

use yii\db\Schema;
use yii\db\Migration;

class m160310_065739_add_modemD_command_conveyor extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('modemD_command_conveyor', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `modemD_command_conveyor` (
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
        echo "m160310_065739_add_modemD_command_conveyor cannot be reverted.\n";

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
