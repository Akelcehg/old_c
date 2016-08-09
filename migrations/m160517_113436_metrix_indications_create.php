<?php

use yii\db\Schema;
use yii\db\Migration;

class m160517_113436_metrix_indications_create extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('metrix_indications', true);

        if(!isset($table)) {
            $this->createTable('metrix_indications', [
                'id' => 'pk',
                'counter_id' => 'bigint(12) UNSIGNED NULL',
                'indications' => 'double(12,3)',
                'created_at' => 'timestamp',
            ]);
        }

    }

    public function down()
    {
        echo "m160517_113436_metrix_indications_create cannot be reverted.\n";

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
