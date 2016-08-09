<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_194553_alter_user_modems extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(!isset($table->columns['update_interval'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `update_interval` tinyint(3) UNSIGNED NULL DEFAULT NULL AFTER invoice_request;");
        }

        $this->createIndex('user_id', 'user_modems', 'user_id');
        $this->createIndex('serial_number', 'user_modems', 'user_id');
        $this->createIndex('signal_level', 'user_modems', 'signal_level');
    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
