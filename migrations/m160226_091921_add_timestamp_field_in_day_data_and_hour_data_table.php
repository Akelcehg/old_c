<?php

use yii\db\Schema;
use yii\db\Migration;

class m160226_091921_add_timestamp_field_in_day_data_and_hour_data_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('HourData', true);
        if(isset($table)) {

            $this->execute("ALTER TABLE `HourData` ADD `timestamp` timestamp AFTER `created_at`;");
        }


        $table = \Yii::$app->db->schema->getTableSchema('DayData', true);
        if(isset($table)) {

            $this->execute("ALTER TABLE `DayData` ADD `timestamp` timestamp AFTER `created_at`;");
        }



    }

    public function down()
    {
        echo "m160226_091921_add_timestamp_field_in_day_data_and_hour_data_table cannot be reverted.\n";

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
