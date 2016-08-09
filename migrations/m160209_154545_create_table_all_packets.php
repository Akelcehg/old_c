<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_154545_create_table_all_packets extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('all_packets', true);
        if(!isset($table)) {
            $this->execute("CREATE TABLE IF NOT EXISTS `all_packets` (
            `id` int(11) NOT NULL,
          `packet` longblob NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE = InnoDB AUTO_INCREMENT = 1360 DEFAULT CHARSET = utf8;");
            }

    }

    public function down()
    {
        echo "m160209_154545_create_table_all_packets cannot be reverted.\n";

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
