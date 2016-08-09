<?php

use yii\db\Schema;
use yii\db\Migration;

class m160105_071235_import_alerts extends Migration
{
    public function up()
    {

        $query= new \yii\db\Query();
        $allAlerts=$query->select('*')->from('alerts_list')->all();


        $connection = Yii::$app->db;



        foreach($allAlerts as $oneAlerts)
        {

            $queryCounter= new \yii\db\Query();
            $counter=$queryCounter->select('*')->from('counters')->where(['rmodule_id'=>$oneAlerts['serial_number']])->one();
            $connection->createCommand()->update('alerts_list',['serial_number'=>$counter['serial_number']],['serial_number'=>$oneAlerts['serial_number']])->execute();

        }


    }

    public function down()
    {
        echo "m160105_071235_import_alerts cannot be reverted.\n";

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
