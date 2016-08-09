<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_135244_create_modem_table extends Migration
{
    public function up()
    {

       /* $this->createTable('modems',[
            'id' => 'pk',
            'user_id' => 'int(12)',
            'serial_number' => 'int(12)',
            'phone_number' => 'varchar(10)',
            'last_invoice_request' => 'varchar(255)',
            'invoice_request' => 'varchar(32)',
            'update_interval'=>'tinyint(3) UNSIGNED NULL DEFAULT NULL ',
            'signal_level' => 'tinyint(10)',
            'balans'=> 'DOUBLE (8,5)',
            'last_temp'=> 'float(8,3) UNSIGNED NULL DEFAULT NULL ',
            'geo_location_id'=>	'int(10)',
            'type' => 'enum(\'built-in\',\'discrete\')',
            'everyday_update_interval'=>'tinyint(2)',
            'alert_datacode1'=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'alert_datacode2'=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'alert_datacode3'=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'alert_datacode4'=> 'int(11) UNSIGNED NULL DEFAULT NULL',
            'alert_type1'=> 'smallint(5) UNSIGNED NULL DEFAULT NULL',
            'alert_type2'=> 'smallint(5) UNSIGNED NULL DEFAULT NULL',
            'alert_type3'=> 'smallint(5) UNSIGNED NULL DEFAULT NULL',
            'alert_type4'=> 'smallint(5) UNSIGNED NULL DEFAULT NULL',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'created_at' => 'timestamp',

        ]);

        $this->execute("ALTER TABLE `user_modems` DROP PRIMARY KEY;");
        $this->execute("ALTER TABLE `user_modems` ADD PRIMARY KEY(`serial_number`);");*/

    }

    public function down()
    {
        echo "m151216_135244_create_modem_table cannot be reverted.\n";

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
