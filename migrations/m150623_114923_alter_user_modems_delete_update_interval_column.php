<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_114923_alter_user_modems_delete_update_interval_column extends Migration
{
    public function up()
    {
             $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(isset($table->columns['update_interval'])) {
            $this->execute("ALTER TABLE user_modems DROP COLUMN update_interval");
        }
        
       
    }

    public function down()
    {
        echo "m150623_114923_alter_user_modems_delete_update_interval_column cannot be reverted.\n";

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
