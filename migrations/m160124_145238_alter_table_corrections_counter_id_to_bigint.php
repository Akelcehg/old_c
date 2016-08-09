<?php

use yii\db\Schema;
use yii\db\Migration;

class m160124_145238_alter_table_corrections_counter_id_to_bigint extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('correction', true);
        if(isset($table->columns['user_counter_id'])) {
            $this->execute("ALTER TABLE `correction` CHANGE `counter_id` `counter_id` bigint(12);");
        }

    }

    public function down()
    {
        echo "m160124_145238_alter_table_corrections_counter_id_to_bigint cannot be reverted.\n";

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
