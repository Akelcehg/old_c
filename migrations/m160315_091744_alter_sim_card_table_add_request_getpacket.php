<?php

use yii\db\Schema;
use yii\db\Migration;

class m160315_091744_alter_sim_card_table_add_request_getpacket extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('sim_card', true);
        if(!isset($table->columns['request_get_packet'])) {
            $this->execute("ALTER TABLE `sim_card` ADD `request_get_packet` VARCHAR (255);");
        }

    }

    public function down()
    {
        echo "m160315_091744_alter_sim_card_table_add_request_getpacket cannot be reverted.\n";

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
