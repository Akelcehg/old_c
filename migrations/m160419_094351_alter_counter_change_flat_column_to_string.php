<?php

use yii\db\Schema;
use yii\db\Migration;

class m160419_094351_alter_counter_change_flat_column_to_string extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('counters', true);
        if(isset($table->columns['flat'])) {
            $this->execute("ALTER TABLE `counters` CHANGE `flat` `flat` varchar(25);");
        }

    }

    public function down()
    {
        echo "m160419_094351_alter_counter_change_flat_column_to_string cannot be reverted.\n";

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
