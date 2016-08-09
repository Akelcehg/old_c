<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_105941_add_counter_models_table extends Migration
{
    //Id name type(gas,water), rate(int)
    public function up()
    {
        
        $isExist = \Yii::$app->db->createCommand("SHOW TABLES LIKE 'counter_models'")->execute();

        if(!$isExist) {
            $this->execute("
                CREATE TABLE `counter_models` (
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `name` varchar(100) NOT NULL,
                 `type` enum('gas','water'),
                 `rate` int(11) NOT NULL ,
                 PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8
            ");
        }
        
    }

    public function down()
    {
        echo "m150608_105941_add_counter_models_table cannot be reverted.\n";

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
