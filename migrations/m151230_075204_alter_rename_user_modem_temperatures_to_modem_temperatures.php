<?php

use yii\db\Schema;
use yii\db\Migration;

class m151230_075204_alter_rename_user_modem_temperatures_to_modem_temperatures extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('user_modem_temparatues', true);
        if(isset($table)){$this->execute("RENAME TABLE user_modem_temparatues TO modem_temparatues;");}

        $table = \Yii::$app->db->schema->getTableSchema('modem_temparatues', true);

        if(isset($table->columns['user_modem_id'])) {
            $this->execute("ALTER TABLE `modem_temparatues` CHANGE `user_modem_id` `modem_id` int(12);");
        }



    }

    public function down()
    {
        echo "m151230_075204_alter_rename_user_modem_temperatures_to_modem_temperatures cannot be reverted.\n";

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
