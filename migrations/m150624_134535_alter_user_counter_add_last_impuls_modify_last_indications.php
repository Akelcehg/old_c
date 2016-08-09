<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_134535_alter_user_counter_add_last_impuls_modify_last_indications extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);
         if(isset($table->columns['last_indications'])) {
            $this->execute("ALTER TABLE `user_counters` MODIFY `last_indications`  float(8,3);");
        }
        
         if(!isset($table->columns['last_impuls'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `last_impuls` int(12) AFTER `update_interval`;");
        }
        
        if(!isset($table->columns['timecode'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `timecode` int(12) AFTER `last_impuls`;");
        }

    }

    public function down()
    {
        echo "m150624_134535_alter_user_counter_add_last_impuls cannot be reverted.\n";

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
