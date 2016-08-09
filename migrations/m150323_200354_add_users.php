<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_200354_add_users extends Migration
{
    public function up()
    {
        $isExist = \Yii::$app->db->createCommand("SHOW TABLES LIKE 'users'")->execute();

        if(!$isExist) {
            $this->execute("
                CREATE TABLE `users` (
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `email` varchar(100) NOT NULL,
                 `password` varchar(100) NOT NULL,
                 `first_name` varchar(50) DEFAULT NULL,
                 `last_name` varchar(50) DEFAULT NULL,
                 `nick_name` varchar(255) NOT NULL,
                 `ip` varchar(20) NOT NULL,
                 `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                 `role` varchar(30) DEFAULT NULL,
                 `status` enum('ACTIVE','DEACTIVATED','WAITING_EMAIL_APPROVE','DELETED') DEFAULT 'ACTIVE',
                 PRIMARY KEY (`id`),
                 UNIQUE KEY `email` (`email`)
                ) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8
            ");
        }
    }

    public function down()
    {
        echo "m150323_200355_alter_user_counters_add_last_indications cannot be reverted.\n";

        return false;
    }
}
