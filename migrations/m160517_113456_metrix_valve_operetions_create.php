<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_113456_metrix_valve_operetions_create extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('metrix_valve_operations', true);

        if(!isset($table)) {

            $this->createTable('metrix_valve_operations', [
                'id' => 'pk',
                'counter_id' => 'bigint(12) UNSIGNED NULL',
                'valve_status' => "enum('open', 'close') NULL",
                'created_at' => 'timestamp',
            ]);
        }

    }

    public function down()
    {
        echo "m160517_113456_metrix_valve_operetions_create cannot be reverted.\n";

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
