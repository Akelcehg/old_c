<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_135132_user_counters_to_counter_and_rmodule extends Migration
{
    public function up()
    {


        $query= new \yii\db\Query();
        $userCountersArray=$query->select('*')->from('user_counters')->all();

        $i=0;$j=0;;
       foreach($userCountersArray as $oneUserCounter){
           $queryCounterArray=[];
           $queryRmodArray=[];
           $countersKeyArray=[

               'user_id',
               'user_modem_id',
               'serial_number',
               'type',
               'initial_indications',
               'last_indications',
               'user_type',
               'geo_location_id',
               'fullname',
               'inn',
               'phone',
               'account',
               'flat',
               'counter_model',
               'created_at',
               'updated_at',
               'real_serial_number'
           ];
           $rmodKeyArray=[

               'user_id',
               'user_modem_id',
               'serial_number',
               'last_impuls',
               'battery_level',
               'timecode',
               'geo_location_id',
               'is_ignore_alert',
               'update_interval',
               'month_update',
               'month_update_type',
               'created_at',
               'updated_at',
           ];

           foreach($oneUserCounter as $key=>$value){

               if(in_array($key,$countersKeyArray)){
                   switch($key)
                   {
                       case 'user_modem_id' :
                           $queryCounterArray['modem_id']=$value;
                           break;
                       case 'serial_number':
                           $queryCounterArray['rmodule_id']=$value;
                           break;
                       case 'real_serial_number':
                           $queryCounterArray['serial_number']=$value;
                           break;
                       default:
                           if(!is_null($value)){$queryCounterArray[$key]=$value;};
                   }}



           }



           $connection = Yii::$app->db;

           if($connection->createCommand()->insert('counters',$queryCounterArray)->execute())
           {
               $i++;
           }





        echo 'counters added:'.$i."\n";



           foreach($oneUserCounter as $key=>$value){

                $counter=\app\models\Counter::findOne(['rmodule_id'=>$oneUserCounter['serial_number']]);


               if(in_array($key,$rmodKeyArray)){
                   switch($key)
                   {

                       case 'user_modem_id' :
                           $queryRmodArray['modem_id']=$value;
                           break;

                       case'last_impuls':
                           $queryRmodArray['last_impulse']=$value;
                           break;

                       default:
                           $queryRmodArray[$key]=$value;
                   }

                   $queryRmodArray['counter_id']=$counter->id;

               }


           }




           if($connection->createCommand()->insert('rmodules',$queryRmodArray)->execute())
           {
               $j++;
           }


       }


        //$this->execute("UPDATE `rmodules` SET `counter_id`=`counters`.`id` WHERE `id`= `counters`.`rmodule_id`;");


    }




    public function down()
    {
        echo "m151217_135132_user_counters_to_counter_and_rmodule cannot be reverted.\n";

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
