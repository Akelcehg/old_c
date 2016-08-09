<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 16.02.16
 * Time: 16:16
 */

namespace app\commands;


use app\components\FlouTechCommandGenerator;
use app\components\FlouTechReportGenerator;
use app\models\CorrectorToCounter;
use app\models\Intervention;
use app\models\ModemStatus;
use yii\console\Controller;


class StatusController extends Controller
{

    public function actionCheck(){
        $this->CheckPromModemStatus();
    }

    public function CheckPromModemStatus(){

        $modems=ModemStatus::find()->all();

        foreach($modems as $modem){

                $dt=\DateTime::createFromFormat($modem->time_on_line);
                $dn=new \DateTime();
                $di=$dn->diff($dt);

           if((40> $di->m)and($di->m >15)){
                $modem->status="Busy";
                $modem->save();
            }

              if($di->m>40){
                $modem->status="Disconnect";
                $modem->save();
            }

        }

    }


}