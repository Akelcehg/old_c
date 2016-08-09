<?php

use yii\db\Schema;
use yii\db\Migration;

class m151123_110719_add_in_users_modem_alertdatacode_and_alerttype_column extends Migration
{
    public function up()
    {
        /*Леша, привет. Добавь пожалуйста в таблицу модемов 4 поля unsigned int alert_datacode1..4  и 4 поля small int alert_type1..4*/
        
        $table = \Yii::$app->db->schema->getTableSchema('user_modems', true);

        if(!isset($table->columns['alert_datacode1'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_datacode1` int(11) UNSIGNED NULL DEFAULT NULL;");
        }
        if(!isset($table->columns['alert_datacode2'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_datacode2` int(11) UNSIGNED NULL DEFAULT NULL;");
        }
        if(!isset($table->columns['alert_datacode3'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_datacode3` int(11) UNSIGNED NULL DEFAULT NULL;");
        }
        if(!isset($table->columns['alert_datacode4'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_datacode4` int(11) UNSIGNED NULL DEFAULT NULL;");
        }	
        if(!isset($table->columns['alert_type1'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_type1` smallint(5) UNSIGNED NULL DEFAULT NULL;");
        }
         if(!isset($table->columns['alert_type2'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_type2` smallint(5) UNSIGNED NULL DEFAULT NULL;");
        }
         if(!isset($table->columns['alert_type3'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_type3` smallint(5) UNSIGNED NULL DEFAULT NULL;");
        }
         if(!isset($table->columns['alert_type4'])) {
            $this->execute("ALTER TABLE `user_modems` ADD `alert_type4` smallint(5) UNSIGNED NULL DEFAULT NULL;");
        }
        
        

    }

    public function down()
    {
        echo "m151123_110719_add_in_users_modem_alertdatacode_and_alerttype_column cannot be reverted.\n";

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
