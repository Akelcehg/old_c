<?php

use yii\db\Schema;
use yii\db\Migration;

class m160713_134522_alter_user_table_add_user_language_field extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('users', true);

        if(!isset($table->columns['language_id'])) {
            $this->execute("ALTER TABLE `users` ADD `language_id` int(10);");
        }

    }

    public function down()
    {
        echo "m160713_134522_alter_user_table_add_user_language_field cannot be reverted.\n";

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
