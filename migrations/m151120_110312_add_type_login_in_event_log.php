<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_110312_add_type_login_in_event_log extends Migration
{
    public function up()
    {
        
         
        $table = \Yii::$app->db->schema->getTableSchema('event_log', true);
        
        
         if(isset($table->columns['type'])) {
             $this->execute("ALTER TABLE `event_log` MODIFY `type`  enum('edit', 'correction', 'alert','login') ;");
        }

        

    }

    public function down()
    {
        echo "m151120_110312_add_type_login_in_event_log cannot be reverted.\n";

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
