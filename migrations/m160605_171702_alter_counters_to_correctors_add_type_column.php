<?php

use yii\db\Schema;
use yii\db\Migration;

class m160605_171702_alter_counters_to_correctors_add_type_column extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('corrector_to_counter', true);
        if(!isset($table->columns['type'])) {
            $this->execute("ALTER TABLE `corrector_to_counter` ADD `type` enum('prom','grs')DEFAULT 'prom';");
        }

    }

    public function down()
    {
        echo "m160605_171702_alter_counters_to_correctors_add_type_column cannot be reverted.\n";

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
