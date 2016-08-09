<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_124328_alter_counter_address_rename_to_address extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('counter_address', true);
        if(isset($table)){$this->execute("RENAME TABLE counter_address TO address;");}


    }

    public function down()
    {
        echo "m151217_124328_alter_counter_address_rename_to_address cannot be reverted.\n";

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
