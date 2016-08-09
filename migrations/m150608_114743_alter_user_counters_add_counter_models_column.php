<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_114743_alter_user_counters_add_counter_models_column extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('user_counters', true);

        if(!isset($table->columns['counter_model'])) {
            $this->execute("ALTER TABLE `user_counters` ADD `counter_model` int(11) AFTER `magnet`;");
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
