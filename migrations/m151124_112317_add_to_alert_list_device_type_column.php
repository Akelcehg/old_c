<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_112317_add_to_alert_list_device_type_column extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('alerts_list', true);

        if(!isset($table->columns['device_type'])) {
            $this->execute("ALTER TABLE `alerts_list` ADD `device_type` enum('counter','modem');");
        }
           
    }

    public function down()
    {
        echo "m151124_112317_add cannot be reverted.\n";

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
