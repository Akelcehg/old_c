<?php

use yii\db\Schema;
use yii\db\Migration;

class m160721_071859_alter_menu_items_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('menu_items', true);

        if(!isset($table->columns['menu_id'])) {
            $this->execute("ALTER TABLE `menu_items` ADD `menu_id` int(11) AFTER `id`;");
        }

    }

    public function down()
    {
        echo "m160721_071859_alter_menu_items_table cannot be reverted.\n";

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
