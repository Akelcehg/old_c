<?php

use yii\db\Schema;
use yii\db\Migration;

class m160706_064555_add_limits_tabs extends Migration
{
    public function up()
    {


        $table = \Yii::$app->db->schema->getTableSchema('limits', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `limits` (
                              `id` int(10) unsigned NOT NULL,
                              `all_id` int(10) unsigned NOT NULL,
                              `limit` int(10) unsigned,
                              `month` VARCHAR (255),
                              `year` VARCHAR (255),
                              `created_at` timestamp,
                              `updated_at` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `limits` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `limits` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160706_064555_add_limits_tabs cannot be reverted.\n";

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
