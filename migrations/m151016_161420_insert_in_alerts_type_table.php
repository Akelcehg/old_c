<?php

use yii\db\Schema;
use yii\db\Migration;

class m151016_161420_insert_in_alerts_type_table extends Migration
{
    public function up()
    {
        
        $this->execute("INSERT INTO `alerts_types` (`id`, `name`) VALUES
                        (1, 'leak'),
                        (2, 'magnet'),
                        (3, 'tamper'),
                        (4, 'low_battery_level'),
                        (5, 'disconnect'),
                        (6, 'low_balance');");
    }

    public function down()
    {
        echo "m151016_161420_insert_in_alerts_type_table cannot be reverted.\n";

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
