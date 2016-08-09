<?php

use yii\db\Schema;
use yii\db\Migration;

class m160115_135108_alter_correction_table_rename_column_user_counter_idti_counter_id extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('correction', true);
        if(isset($table->columns['user_counter_id'])) {
            $this->execute("ALTER TABLE `correction` CHANGE `user_counter_id` `counter_id` int(12);");
        }

    }

    public function down()
    {
        echo "m160115_135108_alter_correction_table_rename_column_user_counter_idti_counter_id cannot be reverted.\n";

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
