<?php

use yii\db\Schema;
use yii\db\Migration;

class m151020_123635_add_everyday_update_in_user_modems_table extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);
        
        
         if(!isset($table->columns['everyday_update_interval'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `everyday_update_interval` tinyint(2);");
        }

    }

    public function down()
    {
        echo "m151020_123635_add_everyday_update_in_user_modems_table cannot be reverted.\n";

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
