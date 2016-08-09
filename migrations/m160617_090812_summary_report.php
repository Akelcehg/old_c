<?php

use yii\db\Schema;
use yii\db\Migration;

class m160617_090812_summary_report extends Migration
{
    public function up()
    {

        $this->createTable('summary_reports',[
            'id'=>'pk',
            'prom'=>'float(12,8)',
            'legal_entity'=>'float(12,8)',
            'house_metering' => 'float(12,8)',
            'individual'=>"float(12,8)",
            'grs'=>'float(12,8)',
            'all'=>'float(12,8)',
            'created_at' => 'timestamp',
        ]);

    }

    public function down()
    {
        echo "m160617_090812_summary_report cannot be reverted.\n";

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
