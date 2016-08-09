<?php

use yii\db\Schema;
use yii\db\Migration;

class m160309_094720_add_table_sim_card extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('sim_card', true);
        if(!isset($table)) {
            $this->execute("CREATE TABLE `sim_card` (
                              `id` int(10) unsigned NOT NULL,
                              `modem_id` int(10) unsigned,
                              `contract_day` int(2),
                              `tarif` int(10),
                              `packet` text,
                              `request_forced_payment` varchar(255),
                              `request_balance` varchar(255)
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `sim_card` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `sim_card` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160309_094720_add_table_sim_card cannot be reverted.\n";

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
