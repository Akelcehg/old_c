<?php

use yii\db\Schema;
use yii\db\Migration;

class m160315_074706_add_sms_history_table extends Migration
{
    public function up()
    {


        $table = \Yii::$app->db->schema->getTableSchema('sms_history', true);
        if(!isset($table)) {
            $this->execute("CREATE TABLE `sms_history` (
                              `id` int(10) unsigned NOT NULL,
                              `modem_id` int(10) unsigned,
                              `sms` text,
                              `date` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `sms_history` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `sms_history` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160315_074706_add_sms_history_table cannot be reverted.\n";

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
