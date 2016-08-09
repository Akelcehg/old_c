<?php

use yii\db\Schema;
use yii\db\Migration;

class m150705_206553_alter_user_modems_add_last_temp extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(!isset($table->columns['last_temp'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `last_temp` float(8,3) UNSIGNED NULL DEFAULT NULL AFTER invoice_request;");
        }

     
    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
