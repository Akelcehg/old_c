<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_100922_add_table_floutech_variable extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('floutech_variables', true);
        if(!isset($table)) {
            $this->execute("
            CREATE TABLE `floutech_variables` (
              `id` int(11) NOT NULL,
              `command_id` int(11) NOT NULL,
              `len` int(11) NOT NULL,
              `order` int(11) NOT NULL,
              `description` VARCHAR (255) NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE = InnoDB AUTO_INCREMENT =0 DEFAULT CHARSET = utf8;

            ALTER TABLE `floutech_variables`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `floutech_variables`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
            ");
        }

    }

    public function down()
    {
        echo "m160212_100922_add_table_floutech_variable cannot be reverted.\n";

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
