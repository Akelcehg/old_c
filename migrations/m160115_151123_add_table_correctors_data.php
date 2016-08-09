<?php

use yii\db\Schema;
use yii\db\Migration;

class m160115_151123_add_table_correctors_data extends Migration
{
    public function up()
    {

        $this->createTable('correctors_data',[
                'id' => 'pk',
                'indication_id'=>'bigint(12)',
                'temperature' => 'bigint(12)',
                'pressure'=>'bigint(12)',
                'uncorrected_indications'=>'double(12:3)',
                'raw_packet'=> 'blob'
            ]
        );

    }

    public function down()
    {
        echo "m160115_151123_add_table_correctors_data cannot be reverted.\n";

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
