<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_193026_user_counters extends Migration
{
    public function up()
    {
        $this->createTable('user_counters',[
            'id' => 'pk',
            'user_id' => 'int(12)',
            'user_modem_id' => 'int(12)',
            'serial_number' => 'varchar(255)',
            'initial_indications' => 'int(12)',
            'update_interval' => 'tinyint(10)',
            'battery_level' => 'tinyint(5)',
            'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'created_at' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m150323_193026_user_counters cannot be reverted.\n";

        return false;
    }
}
