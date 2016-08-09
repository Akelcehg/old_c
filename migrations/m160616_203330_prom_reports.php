<?php

use yii\db\Schema;
use yii\db\Migration;

class m160616_203330_prom_reports extends Migration
{
    public function up()
    {

        $this->createTable('prom_reports',[
            'id'=>'pk',
            'all_id'=>'bigint(12) UNSIGNED NULL',
            'is_valid'=>'int(1) default 0',
            'date' => 'timestamp',
            'report_type'=>"enum('day','month')  NULL",
            'errors'=>'text',
            'created_at' => 'timestamp',
        ]);

    }

    public function down()
    {
        echo "m160616_203330_reports cannot be reverted.\n";

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
