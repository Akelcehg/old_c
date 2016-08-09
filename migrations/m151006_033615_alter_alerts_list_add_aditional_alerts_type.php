<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_033615_alter_alerts_list_add_aditional_alerts_type extends Migration
{
    public function up()
    {
   
        
        $this->execute("ALTER TABLE `alerts_list` CHANGE `type` `type` 	enum('leak', 'magnet', 'tamper','lowBatteryLevel','disconnect') ");

    
    }

    public function down()
    {
        echo "m151006_033615_alter_alerts_list_add_aditional_alerts_type cannot be reverted.\n";

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
