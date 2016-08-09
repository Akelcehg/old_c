<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_192010_user_modems extends Migration
{
    public function up()
    {
        $this->createTable('user_modems',[
            'id' => 'pk',
            'user_id' => 'int(12)',
            'serial_number' => 'varchar(255)',
            'phone_number' => 'varchar(10)',
            'last_invoice_request' => 'varchar(255)',
            'invoice_request' => 'varchar(32)',
            'signal_level' => 'tinyint(10)',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'created_at' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m150323_192010_user_modems cannot be reverted.\n";

        return false;
    }
}
