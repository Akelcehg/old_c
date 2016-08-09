<?php

use yii\db\Schema;
use yii\db\Migration;

class m160310_123632_default_modem_sim_preferences extends Migration
{
    public function up()
    {

        $modem = \app\models\Modem::find()->all();
        foreach($modem as $oneModem){
            $simcard=$oneModem->simCard;
            $simcard->request_balance = "*111#";
            $simcard->save();
        }

    }

    public function down()
    {
        echo "m160310_123632_default_modem_sim_preferences cannot be reverted.\n";

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
