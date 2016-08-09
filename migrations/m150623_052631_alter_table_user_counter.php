<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_052631_alter_table_user_counter extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        if(!isset($table->columns['update_interval'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `update_interval` tinyint(3) AFTER `counter_model`;");
        }
        
        if(isset($table->columns['initial_indications'])) {
            $this->execute("ALTER TABLE `user_counters` MODIFY `initial_indications`  float(8,3) ;");
        }
    

    }

    public function down()
    {
        echo "m150623_052631_alter_table_user_counter cannot be reverted.\n";

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
