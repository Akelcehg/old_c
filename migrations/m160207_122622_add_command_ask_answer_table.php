<?php

use yii\db\Schema;
use yii\db\Migration;

class m160207_122622_add_command_ask_answer_table extends Migration
{
    public function up()
    {


        $table = \Yii::$app->db->schema->getTableSchema('command_ask_answer', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `command_ask_answer` (
                                `id` int unsigned AUTO_INCREMENT,
                                `modem_id` int,
                                `command_conveyor_id` int,
                                `prev_query_id` int,
                                `ask` varchar(255),
                                `answer` varchar(255),
                                `created_at` timestamp NULL   DEFAULT CURRENT_TIMESTAMP,
                                `answered_at` timestamp NULL ,
                                 PRIMARY KEY (`id`))
                                 ;");
        }



    }

    public function down()
    {
        echo "m160207_122622_add_command_ask_answer_table cannot be reverted.\n";

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
