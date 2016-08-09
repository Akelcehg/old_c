<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_091438_add_user_counter_add_month_update extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);
        
        
         if(!isset($table->columns['month_update'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `month_update` TIMESTAMP NOT NULL AFTER `update_interval`;");
        }

    }

    public function down()
    {
        echo "m150713_091438_add_user_counter_add_month_update cannot be reverted.\n";

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
