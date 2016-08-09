<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_065433_alter_table_user_indications_modify_indications extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('user_indications', true);
         if(isset($table->columns['indications'])) {
            $this->execute("ALTER TABLE `user_indications` MODIFY `indications`  float(8,3) ;");
        }

    }

    public function down()
    {
        echo "m150623_065433_alter_table_user_indications_modify_indications cannot be reverted.\n";

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
