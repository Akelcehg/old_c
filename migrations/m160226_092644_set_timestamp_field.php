<?php

use yii\db\Schema;
use yii\db\Migration;

class m160226_092644_set_timestamp_field extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('HourData', true);
        if(isset($table)) {

            $this->execute("UPDATE `HourData` SET `timestamp`=TIMESTAMP(CONCAT(2000+year,\"-\",month,\"-\",day,\" \",hour_n,\":\",minutes_n,\":\",seconds_n)) WHERE 1 ");
        }


        $table = \Yii::$app->db->schema->getTableSchema('DayData', true);
        if(isset($table)) {

            $this->execute("UPDATE `DayData` SET `timestamp`=TIMESTAMP(CONCAT(2000+year,\"-\",month,\"-\",day,\" \",hour,\":\",minutes,\":\",seconds)) WHERE 1 ");
        }

    }

    public function down()
    {
        echo "m160226_092644_set_timestamp_field cannot be reverted.\n";

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
