<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_114746_alter_counter_address_rename_address_to_street_add_house extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('counter_address', true);
         
         if(isset($table->columns['address'])) {
            $this->execute("ALTER TABLE `counter_address` CHANGE `address` `street` text ;");
        }

         if(!isset($table->columns['house'])) {
            $this->execute("ALTER TABLE `counter_address` ADD `house` varchar(5) AFTER `street` ;");
        }

    }

    public function down()
    {
        echo "m150608_114743_alter_user_counters_add_counter_models_column cannot be reverted.\n";

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
