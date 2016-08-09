<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_091109_delete_form_counters_add_to_address_expluatation extends Migration
{
    public function up()
    {
        
       $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);
        
        
         if(isset($table->columns['exploitation'])) {
            $this->execute("ALTER TABLE `user_counters` DROP `exploitation`;");
        }
        
        $table1 = \Yii::$app->db->schema->getTableSchema('counter_address', true);
        
        
         if(!isset($table1->columns['exploitation'])) {
            $this->execute("ALTER TABLE `counter_address` ADD `exploitation` tinyint(1) DEFAULT 0 ;");
        }

    }

    public function down()
    {
        echo "m151030_091109_delete_form_counters_add_to_address_expluatation cannot be reverted.\n";

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
