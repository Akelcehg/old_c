<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_135312_create_counters_table extends Migration
{
    public function up()
    {

        $this->createTable('counters',[
            'id'=>'pk',
            'user_id'=>'bigint(12) UNSIGNED NULL',
            'modem_id'=>'bigint(12) UNSIGNED NULL',
            'rmodule_id'=>'bigint(12) UNSIGNED NULL',

            'serial_number'=>'bigint(12)',
            'type'=>"enum('gas', 'water') NULL",
            'counter_model'=>'int(11)NULL',
            'initial_indications'=>	'double(12,3) NULL',
            'last_indications'=>'double(12,3) NULL',
            'geo_location_id'=>'int(11)',


            'user_type'=>"enum('individual', 'legal_entity')  NULL",
            'fullname'=>'varchar(255)NULL',
            'inn'=>'varchar(255) NULL',
            'phone'=>'varchar(255) NULL',
            'account'=>	'varchar(255)',
            'flat'=>'int(255)',

            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'created_at' => 'timestamp',

        ]);

    }

    public function down()
    {
        echo "m151216_135312_create_counters_table cannot be reverted.\n";

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
