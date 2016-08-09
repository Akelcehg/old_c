<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_143101_alter_user_models_add_geo_location_id extends Migration
{
    public function up()
    {
         $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);
        
         if(!isset($table->columns['geo_location_id'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `geo_location_id` int(10) AFTER `signal_level`;");
        }
         	

    }

    public function down()
    {
        echo "m150624_143101_alter_user_models_add_geo_location_id cannot be reverted.\n";

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
