<?php

use yii\db\Schema;
use yii\db\Migration;

class m160322_081230_alert_alerts_list_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('alerts_list', true);

        if(isset($table->columns['type'])) {
            $this->execute("ALTER TABLE `alerts_list` CHANGE `type` `type` varchar(255);");
        }

        if(isset($table->columns['device_type'])) {
            $this->execute("ALTER TABLE `alerts_list` CHANGE `device_type` `device_type` enum('counter' , 'modem' , 'prom');");
        }

    }

    public function down()
    {
        echo "m160322_081230_alert_alerts_list_table cannot be reverted.\n";

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
