<?php

use yii\db\Schema;
use yii\db\Migration;

class m150723_161029_user_alter_add_confirm_code extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('confirm_code', true);

         if(!isset($table->columns['confirm_code'])) {
            $this->execute("ALTER TABLE `users` ADD `confirm_code` varchar(255) NOT NULL AFTER `legal_address`;");
        }
        
    }

    public function down()
    {
        echo "m150723_161029_user_alter_add_confirm_code cannot be reverted.\n";

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
