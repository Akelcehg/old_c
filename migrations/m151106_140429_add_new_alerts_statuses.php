<?php

use yii\db\Schema;
use yii\db\Migration;

class m151106_140429_add_new_alerts_statuses extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('alerts_list', true);
        
        
         if(isset($table->columns['status'])) {
             $this->execute("ALTER TABLE `alerts_list` MODIFY `status`  enum('ACTIVE','DEACTIVATED','WAITING','INWORK') ;");
        }


    }

    public function down()
    {
        echo "m151106_140429_add_new_alerts_statuses cannot be reverted.\n";

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
