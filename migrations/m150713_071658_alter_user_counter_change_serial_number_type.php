<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_071658_alter_user_counter_change_serial_number_type extends Migration
{
    public function up()
    {
        $this->execute("set sql_mode = 'STRICT_ALL_TABLES'");

        
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        if(isset($table->columns['serial_number'])) {
            $this->execute("ALTER TABLE `user_counters` MODIFY `serial_number`  varchar(30) ;");
        }
        
        if(isset($table->columns['real_serial_number'])) {
            $this->execute("ALTER TABLE `user_counters` MODIFY `real_serial_number`  varchar(30) ;");
        }

    }

    public function down()
    {
        echo "m150713_071658_alter_user_counter_change_serial_number_type cannot be reverted.\n";

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
