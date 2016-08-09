<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_113509_metrix_alerts_create extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('metrix_alerts', true);

        if(!isset($table)) {

            $this->createTable('metrix_alerts', [
                'id' => 'pk',
                'counter_id' => 'bigint(12) UNSIGNED NULL',
                'type' => "varchar(255)",
                'created_at' => 'timestamp',
            ]);
        }


    }

    public function down()
    {
        echo "m160517_113509_metrix_alerts_create cannot be reverted.\n";

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
