<?php

use yii\db\Schema;
use yii\db\Migration;

class m160229_094723_add_table_search extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('search', true);
        if(!isset($table)) {
            $this->execute("
                              CREATE TABLE `search` (
                              `id` int(10) unsigned NOT NULL,
                              `search_string` varchar(255),
                              `counter_id` float(11) DEFAULT NULL,
                              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ");
        }

    }

    public function down()
    {
        echo "m160229_094723_add_table_search cannot be reverted.\n";

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
