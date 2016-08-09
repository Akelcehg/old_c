<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_154557_create_prom_admin_role extends Migration
{
    public function up()
    {

        Yii::$app->authManager->createRole("prom_admin");

    }

    public function down()
    {
        echo "m160209_154557_create_prom_admin_role cannot be reverted.\n";

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
