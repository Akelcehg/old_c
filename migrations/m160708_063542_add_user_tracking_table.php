<?php

use yii\db\Schema;
use yii\db\Migration;

class m160708_063542_add_user_tracking_table extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('users_tracking', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `users_tracking` (
                              `id` int(10) unsigned NOT NULL,
                              `user_id` int(10) unsigned NOT NULL,
                              `url` text,
                              `refferer` text,
                              `user_action` text,
                              `time_in` timestamp  DEFAULT '0000-00-00 00:00:00',
                              `time_out` timestamp  DEFAULT '0000-00-00 00:00:00',
                              `created_at` timestamp  DEFAULT '0000-00-00 00:00:00'
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `users_tracking` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `users_tracking` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160708_063542_add_user_tracking_table cannot be reverted.\n";

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
