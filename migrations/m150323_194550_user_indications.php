<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_194550_user_indications extends Migration
{
    public function up()
    {
        $this->createTable('user_indications',[
            'id' => 'pk',
            'user_counter_id' => 'int(12)',
            'indications' => 'int(12)',
            'created_at' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
