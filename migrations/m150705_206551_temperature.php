<?php

use yii\db\Schema;
use yii\db\Migration;

class m150705_206551_temperature extends Migration
{
    public function up()
    {
        $this->createTable('user_modem_temparatues',[
            'id' => 'pk',
            'user_modem_id' => 'int(12)',
            'temp' => 'float(8,3)',
            'created_at' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
