<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_114745_alter_users_add_counter_user_type extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('users', true);

         if(!isset($table->columns['user_type'])) {
            $this->execute("ALTER TABLE `users` ADD `user_type` ENUM('individual','legal_entity') AFTER `geo_location_id`;");
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
