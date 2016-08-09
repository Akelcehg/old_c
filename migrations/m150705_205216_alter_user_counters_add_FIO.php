<?php

use yii\db\Schema;
use yii\db\Migration;

class m150705_205216_alter_user_counters_add_FIO extends Migration
{
    public function up()
    {
        
          $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        
        if(!isset($table->columns['fullname'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `fullname` varchar(255) AFTER `update_interval`;");
        }
        
        if(!isset($table->columns['user_type'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `user_type` ENUM('individual','legal_entity') AFTER `update_interval`;");
        }

    }

    public function down()
    {
        echo "m150705_205216_alter_user_counters_add_FIO cannot be reverted.\n";

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
