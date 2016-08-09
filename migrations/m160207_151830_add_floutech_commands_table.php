<?php

use yii\db\Schema;
use yii\db\Migration;

class m160207_151830_add_floutech_commands_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('floutech_commands', true);
        if(!isset($table)) {
            $this->execute("CREATE TABLE  `floutech_commands` (
                              `id` int(11) NOT NULL,
                              `command` varchar(255) DEFAULT NULL,
                              `description` varchar(255) DEFAULT NULL,
                              `created_at` timestamp NULL DEFAULT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;;");
        }

    }

    public function down()
    {
        echo "m160207_151830_add_floutech_commands_table cannot be reverted.\n";

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
