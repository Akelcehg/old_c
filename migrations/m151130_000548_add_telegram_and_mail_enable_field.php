<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_000548_add_telegram_and_mail_enable_field extends Migration
{
    public function up()
    {
        
         $table = \Yii::$app->db->schema->getTableSchema('users', true);
        if(!isset($table->columns['telegram_notification_enable'])) {
            $this->execute("ALTER TABLE `users` ADD `telegram_notification_enable` tinyint(1);");
        }
        if(!isset($table->columns['email_notification_enable'])) {
            $this->execute("ALTER TABLE `users` ADD `email_notification_enable` tinyint(1);");
        }

    }

    public function down()
    {
        echo "m151130_000548_add_telegram_and_mail_enable_field cannot be reverted.\n";

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
