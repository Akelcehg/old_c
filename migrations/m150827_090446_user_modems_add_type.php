<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_090446_user_modems_add_type extends Migration
{
    public function up()
    {
        

        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(!isset($table->columns['update_interval'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `type` enum('built-in','discrete') AFTER invoice_request;");
        }


    

    }

    public function down()
    {
        echo "m150827_090446_user_modems_add_type cannot be reverted.\n";

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
