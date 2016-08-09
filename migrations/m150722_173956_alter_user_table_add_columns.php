<?php

use yii\db\Schema;
use yii\db\Migration;

class m150722_173956_alter_user_table_add_columns extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('users', true);

         if(!isset($table->columns['address'])) {
            $this->execute("ALTER TABLE `users` ADD `address` varchar(255) NOT NULL AFTER `user_type`;");
        }
        
         if(!isset($table->columns['inn'])) {
            $this->execute("ALTER TABLE `users` ADD `inn` varchar(255) NOT NULL AFTER `address`;");
        }
        
         if(!isset($table->columns['facility'])) {
            $this->execute("ALTER TABLE `users` ADD `facility` varchar(255) NOT NULL AFTER `inn`;");
        }
        
         if(!isset($table->columns['legal_address'])) {
            $this->execute("ALTER TABLE `users` ADD `legal_address` varchar(255) NOT NULL AFTER `facility`;");
        }
       
    }

    public function down()
    {
        echo "m150722_173956_alter_user_table_add_columns cannot be reverted.\n";

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
