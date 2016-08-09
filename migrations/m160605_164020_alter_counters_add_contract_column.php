<?php

use yii\db\Schema;
use yii\db\Migration;

class m160605_164020_alter_counters_add_contract_column extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('counters', true);

        if(!isset($table->columns['contract'])) {
            $this->execute("ALTER TABLE `counters` ADD `contract` VARCHAR (255);");
        }
    }

    public function down()
    {
        echo "m160605_164020_alter_counters_add_contract_column cannot be reverted.\n";

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
