<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_135259_create_rmodules_table extends Migration
{
    public function up()
    {
        $this->createTable('rmodules',[
                'id' => 'pk',
                'user_id'=>'bigint(12) UNSIGNED NULL',
                'modem_id' => 'bigint(12) UNSIGNED NULL',
                'counter_id'=>'bigint(12) UNSIGNED NULL'
                ,
                'serial_number' => 'bigint(12) UNSIGNED NULL',
                'last_impulse' => 'int(12)',
                'battery_level'=>'tinyint(5) UNSIGNED NULL',
                'timecode'=>'int(10) UNSIGNED',
                'geo_location_id'=>'bigint(12)',
                'is_ignore_alert'=>'tinyint(1) NULL',

                'update_interval'=>	'smallint(5) UNSIGNED DEFAULT 1',

                'month_update_type'=>"enum('once', 'every_day')",

                'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                'created_at' => 'timestamp',
                'month_update'=> 'timestamp',
            ]
        );

    }

    public function down()
    {
        echo "m151216_135259_create_rmodules_table cannot be reverted.\n";

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
