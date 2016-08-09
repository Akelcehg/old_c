<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_142120_alter_user_indication_add_impulse extends Migration
{
    public function up()
    {
        
        $table = \Yii::$app->db->schema->getTableSchema('user_indications', true);
        
         if(!isset($table->columns['impuls'])) {
            $this->execute("ALTER TABLE `user_indications` ADD `impuls` int(12) AFTER `indications`;");
        }
        
        
    }

    public function down()
    {
        echo "m150624_142120_alter_user_indication_add_impulse cannot be reverted.\n";

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
