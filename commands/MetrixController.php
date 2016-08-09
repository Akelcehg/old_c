<?php

namespace app\commands;

use app\components\Balance;
use app\models\CorrectorToCounter;
use app\modules\metrix\models\MetrixCommandConveyor;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class MetrixController extends Controller
{
    const GET_DATA_COMMAND='105B005B16';


    private function IsActiveCommand($modem_id,$command){

        $command=MetrixCommandConveyor::find()
            ->where(['modem_id'=>$modem_id])
            ->andWhere(['command'=>$command])
            ->andWhere(['status'=>'ACTIVE'])
            ->one();

        if(!empty($command)){
            return true;
        }else{
           return false;
        }
    }

    public function actionSetGetDataCommand(){

        $metrix=CorrectorToCounter::find()
            ->where(['device_type'=>'metrix'])
            ->all();

        foreach($metrix as $oneMetrix){
            if(!$this->IsActiveCommand($oneMetrix->modem_id,self::GET_DATA_COMMAND)){
               $command=new MetrixCommandConveyor();
                $command->modem_id=$oneMetrix->modem_id;
                $command->command=self::GET_DATA_COMMAND;
                $command->command_type=2;
                $command->status='ACTIVE';
                $command->save();
            }
        }
    }


}