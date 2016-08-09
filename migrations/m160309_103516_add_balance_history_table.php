<?php

use yii\db\Schema;
use yii\db\Migration;

class m160309_103516_add_balance_history_table extends Migration
{
    public function up()
    {

          $table = \Yii::$app->db->schema->getTableSchema('balance_history', true);
        if(!isset($table)) {
            $this->execute("CREATE TABLE `balance_history` (
                              `id` int(10) unsigned NOT NULL,
                              `modem_id` int(10) unsigned,
                              `balance` float(12,3),
                              `date` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ");
        }


    }

    public function down()
    {
        echo "m160309_103516_add_balance_history_table cannot be reverted.\n";

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
