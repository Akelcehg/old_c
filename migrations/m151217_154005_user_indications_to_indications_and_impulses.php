<?php

use yii\db\Schema;
use yii\db\Migration;

class m151217_154005_user_indications_to_indications_and_impulses extends Migration
{
    public function up()
    {

        $query1= new \yii\db\Query();
        $userCountersCount=$query1->from('user_indications')->count();

       // $query= new \yii\db\Query();
       // $userCountersArray=$query->from('user_indications')->all();


        $i=0;$j=0;$k=0;
        foreach(  \app\models\UserIndications::find()->each(50) as $oneUserCounter){
            $queryCounterArray=[];
            $queryRmodArray=[];
            $countersKeyArray=[

                'id',
                'user_counter_id',
                'indications',
                'created_at'
            ];
            $rmodKeyArray=[

                'id',
                'impuls',
                'created_at'

            ];

            foreach($oneUserCounter as $key=>$value){

                if(in_array($key,$countersKeyArray)){
                    switch($key)
                    {
                        case 'user_counter_id' :
                            $query= new \yii\db\Query();
                            $counter=$query->from('counters')->where('rmodule_id ='.$value)->one();
                            $queryCounterArray['counter_id']=$counter['id'];
                            break;
                        default:
                            if(!is_null($value)){$queryCounterArray[$key]=$value;};
                    }}


                if(in_array($key,$rmodKeyArray)){
                    switch($key)
                    {
                        case 'impuls' :
                            $queryRmodArray['impulse']=$value;
                            break;
                        case 'id' :
                            $queryRmodArray['indication_id']=$value;
                            break;

                        default:
                            $queryRmodArray[$key]=$value;
                    }
                }


            }



            $connection = Yii::$app->db;

            if($connection->createCommand()->insert('indications',$queryCounterArray)->execute())
            {
                $i++;
            }

            if($connection->createCommand()->insert('impulses',$queryRmodArray)->execute())
            {
                $j++;
            }


            echo $k++."/".$userCountersCount."\n";
        }




        echo 'indications added:'.$i.' impulses added'.$j."\n";

    }




    public function down()
    {
        echo "m151217_154005_user_indications_to_indications_and_impulses cannot be reverted.\n";

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
