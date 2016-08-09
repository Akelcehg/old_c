<?php

use yii\db\Schema;
use yii\db\Migration;

class m160727_070635_alter_corrector_to_counter_add_update_interval_field extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('corrector_to_counter', true);

        if(!isset($table->columns['update_interval'])) {
            $this->execute("ALTER TABLE `corrector_to_counter` ADD `update_interval` int(11);");
        }

    }

    public function down()
    {
        echo "m160727_070635_alter_corrector_to_counter_add_update_interval_field cannot be reverted.\n";

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
