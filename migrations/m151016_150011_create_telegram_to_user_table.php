<?php


use yii\db\Migration;

class m151016_150011_create_telegram_to_user_table extends Migration
{
    public function up()
    {
        
           $this->createTable('telegram_to_user',[
            'id' => 'pk',
            'user_id' => 'int(12)',
            'telegram_id' => 'varchar(255)',
        ]);

    }

    public function down()
    {
        echo "m151016_150011_create_telegram_to_user_table cannot be reverted.\n";

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
