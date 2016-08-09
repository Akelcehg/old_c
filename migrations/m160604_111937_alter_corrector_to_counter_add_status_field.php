<?php

use yii\db\Schema;
use yii\db\Migration;

class m160604_111937_alter_corrector_to_counter_add_status_field extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('corrector_to_counter', true);
        if(!isset($table->columns['hw_status'])) {
            $this->execute("ALTER TABLE `corrector_to_counter` ADD `hw_status` enum('ENABLED','DISABLED')DEFAULT 'ENABLED';");
        }


    }

    public function down()
    {
        echo "m160604_111937_alter_corrector_to_counter_add_status_field cannot be reverted.\n";

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
