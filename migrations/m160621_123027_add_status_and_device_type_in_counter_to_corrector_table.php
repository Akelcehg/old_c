<?php

use yii\db\Schema;
use yii\db\Migration;

class m160621_123027_add_status_and_device_type_in_counter_to_corrector_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('corrector_to_counter', true);
        if(!isset($table->columns['work_status'])) {
            $this->execute("ALTER TABLE `corrector_to_counter` ADD `work_status` enum('test','work')DEFAULT 'test';");
        }

        if(!isset($table->columns['device_type'])) {
            $this->execute("ALTER TABLE `corrector_to_counter` ADD `device_type` varchar(255);");
        }
    }

    public function down()
    {
        echo "m160621_123027_add_status_and_device_type_in_counter_to_corrector_table cannot be reverted.\n";

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
