<?php

use yii\db\Schema;
use yii\db\Migration;

class m151230_080719_copy_table_user_modems_to_modems extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(isset($table)){$this->execute("ALTER TABLE `user_modems` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00';");}
        if(isset($table)){$this->execute("RENAME TABLE user_modems TO modems;");}

    }

    public function down()
    {
        echo "m151230_080719_copy_table_user_modems_to_modems cannot be reverted.\n";

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
