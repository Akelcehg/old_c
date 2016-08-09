<?php

use yii\db\Schema;
use yii\db\Migration;

class m150805_121407_alter_user_counter_add_is_ignore_alert extends Migration
{
    public function up()
    {
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);
             if(!isset($table->columns['is_ignore_alert'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `is_ignore_alert` tinyint(1) AFTER fullname;");
        }
    }

    public function down()
    {
        echo "m150805_121407_alter_user_counter_add_is_ignore_alert cannot be reverted.\n";

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
