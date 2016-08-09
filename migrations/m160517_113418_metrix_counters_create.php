<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_113418_metrix_counters_create extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('metrix_counters', true);

        if(!isset($table)) {

            $this->createTable('metrix_counters', [
                'id' => 'pk',
                'modem_id' => 'bigint(12)',
                'serial_number' => 'bigint(12)',
                'valve_status' => "enum('open', 'close') NULL",
                'geo_location_id' => 'int(11)',
                'updated_at' => 'timestamp',
                'created_at' => 'timestamp',

            ]);
        }

    }

    public function down()
    {
        echo "m160517_113418_metrix_counters_create cannot be reverted.\n";

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
