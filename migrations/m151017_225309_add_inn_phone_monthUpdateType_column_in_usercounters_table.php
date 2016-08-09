<?php

use yii\db\Schema;
use yii\db\Migration;

class m151017_225309_add_inn_phone_monthUpdateType_column_in_usercounters_table extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        if(!isset($table->columns['inn'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `inn` varchar(255);");
        }
        
        if(!isset($table->columns['phone'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `phone` varchar(255);");
        }
        
        if(!isset($table->columns['month_update_type'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `month_update_type` enum('once', 'every_day') DEFAULT 'once';");
        }

    }

    public function down()
    {
        echo "m151017_225309_add_inn_phone_monthUpdateType_column_in_usercounters_table cannot be reverted.\n";

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
