<?php

use yii\db\Schema;
use yii\db\Migration;

class m160207_142609_add_corrector_to_counter_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('corrector_to_counter', true);
        if(!isset($table)) {
            $this->execute(" CREATE TABLE  `corrector_to_counter` (
                                `id` int(11) NOT NULL,
                              `counter_id` int(11) DEFAULT NULL,
                              `corrector_id` int(11) DEFAULT NULL,
                              `branch_id` int(11) DEFAULT NULL,
                              `geo_location_id` int(11) DEFAULT NULL,
                              `created_at` timestamp NULL DEFAULT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

    }

    public function down()
    {
        echo "m160207_142609_add_corrector_to_counter_table cannot be reverted.\n";

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
