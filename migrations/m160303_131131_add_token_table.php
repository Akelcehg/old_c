<?php

use yii\db\Schema;
use yii\db\Migration;

class m160303_131131_add_token_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('users', true);

        if(!isset($table->columns['token'])) {
            $this->execute("ALTER TABLE `users` ADD `token` varchar(255) NOT NULL ;");
        }

    }

    public function down()
    {
        echo "m160303_131131_add_token_table cannot be reverted.\n";

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
