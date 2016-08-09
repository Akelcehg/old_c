<?php

use yii\db\Schema;
use yii\db\Migration;

class m160303_084416_add_prom_info_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('prom_info', true);
        if(!isset($table)) {
            $this->execute("
                              CREATE TABLE `prom_info` (
                              `id` int(10) unsigned NOT NULL,
                              `uegg_ps` varchar(255),
                              `np` varchar(255),
                              `number_ca` varchar(255),
                              `company` varchar(255),
                              `address` varchar(255),
                              `contract` varchar(255)
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `prom_info` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `prom_info` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160303_084416_add_prom_info_table cannot be reverted.\n";

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
