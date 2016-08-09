<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_123846_edit_users_add_energy_code_delete_role extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('users', true);
        if(!isset($table->columns['energy_code'])) {
            $this->execute("ALTER TABLE `users` ADD `energy_code` varchar(255)");
        }

        if(isset($table->columns['role'])) {
            $this->execute("ALTER TABLE `users` DROP `role`;");
        }

    }

    public function down()
    {
        echo "m151217_123846_edit_users_add_energy_code_delete_role cannot be reverted.\n";

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
