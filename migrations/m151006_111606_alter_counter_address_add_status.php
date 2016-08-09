<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_111606_alter_counter_address_add_status extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('counter_address', true);

        if(!isset($table->columns['status'])) {
            $this->execute("ALTER TABLE `counter_address` ADD `status` enum('ACTIVE','DEACTIVATED','DELETED') COLLATE utf8_unicode_ci DEFAULT 'ACTIVE' AFTER `latitude`;");
        }

    }

    public function down()
    {
        echo "m151006_111606_alter_counter_address_add_status cannot be reverted.\n";

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
