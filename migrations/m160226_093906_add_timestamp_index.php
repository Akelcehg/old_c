<?php

use yii\db\Schema;
use yii\db\Migration;

class m160226_093906_add_timestamp_index extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('HourData', true);
        if(isset($table)) {

            $this->execute("ALTER TABLE HourData ADD INDEX(timestamp);");
            $this->execute("ALTER TABLE HourData ADD INDEX(all_id);");
        }


        $table = \Yii::$app->db->schema->getTableSchema('DayData', true);
        if(isset($table)) {

            $this->execute("ALTER TABLE DayData ADD INDEX(timestamp);");
            $this->execute("ALTER TABLE DayData ADD INDEX(all_id);");
        }

    }

    public function down()
    {
        echo "m160226_093906_add_timestamp_index cannot be reverted.\n";

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
