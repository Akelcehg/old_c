<?php

use yii\db\Schema;
use yii\db\Migration;

class m160721_071842_add_menus_table extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('menus', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `menus` (
                              `id` int(10) unsigned NOT NULL,
                              `alias` VARCHAR (255),
                              `name` VARCHAR (255),
                              `created_at` timestamp,
                              `updated_at` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `menus` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `menus` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }


    }

    public function down()
    {
        echo "m160721_071842_add_menus_table cannot be reverted.\n";

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
