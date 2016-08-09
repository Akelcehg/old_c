<?php

use yii\db\Schema;
use yii\db\Migration;

class m160721_120111_add_menus_label_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('menus_label', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `menus_label` (
                              `id` int(10) unsigned NOT NULL,
                              `menu_item_id` VARCHAR (255),
                              `lang_id`  int(10) unsigned NOT NULL,
                              `label` VARCHAR (255),
                              `created_at` timestamp,
                              `updated_at` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `menus_label` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `menus_label` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160721_120111_add_menus_label_table cannot be reverted.\n";

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
