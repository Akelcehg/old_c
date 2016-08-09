<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 10.03.16
 * Time: 13:17
 */


namespace app\commands;

use app\models\BalanceHistory;
use app\models\Modem;
use app\models\ModemDCommandConveyor;
use app\models\SimCard;
use app\models\SmsHistory;
use yii\console\Controller;


class ModemController extends Controller
{

    function isActiveCommand($modem_id){

        $command=ModemDCommandConveyor::find()->where(['modem_id'=>$modem_id,'status'=>'ACTIVE'])->one();
        if($command){
            return $command;
        }else{
            return false;
        }
    }

    function  isPendingCommand($modem_id,$command){

        $command=ModemDCommandConveyor::find()->where(['modem_id'=>$modem_id,'command'=>$command,'status'=>'PENDING'])->one();

        if($command){
            return $command;
        }else{
            return false;
        }

    }

    function nextCommand($modem){
        if($command = $this->isActiveCommand($modem->serial_number)){
            $modem->invoice_request=$command->command;
            $modem->save();
            $command->pending_at=date("Y-m-d H:i:s");
            $command->status="PENDING";
            $command->save();
        }else{
            $modem->invoice_request=$modem->simCard->request_balance;
            $modem->save();
        }
    }

      function getPacketName($modem){

          if($modem->invoice_request==$modem->simCard->request_get_packet){

              $simCard=SimCard::find()->where(["modem_id"=>$modem->serial_number])->one();
              $simCard->packet=$modem->last_invoice_request;

             $simCard->save();



          }

    }


    function parseBalance($modem){
        $result=[];

        preg_match('/\s\d*\.\d*\s?/',$modem->last_invoice_request,$result);

        if(isset($result[0])){
            $modem->balans=$result[0];

            $find=BalanceHistory::find()->where(['modem_id'=>$modem->serial_number])->orderBy(['date'=>SORT_DESC])->one();
            if(empty($find) || $find->balance!=$result[0]) {
                $balanceH = new BalanceHistory();
                $balanceH->modem_id = $modem->serial_number;
                $balanceH->balance = $result[0];
                $balanceH->save();
            }
        }
    }

    function addSmsHistory($modem)
    {

        $find = SmsHistory::find()->where(['modem_id' => $modem->serial_number])->orderBy(['date' => SORT_DESC])->one();
        if (empty($find) || $find->sms != $modem->last_invoice_request) {
            $sms = new SmsHistory();
            $sms->modem_id = $modem->serial_number;
            $sms->sms = $modem->last_invoice_request;
            $sms-> save();
        }
    }

    function actionGetallpacketname()
    {

        $modems=Modem::find()->all();

        foreach ( $modems as $modem){

            $modemDconv=new ModemDCommandConveyor();
            $modemDconv->modem_id=$modem->serial_number;
            $modemDconv->command=$modem->simCard->request_get_packet;
            $modemDconv->status="ACTIVE";
            $modemDconv->save();

        }

    }



    function actionCheck()
    {



        $modems=Modem::find()->all();

        foreach ( $modems as $modem){
            $this->addSmsHistory($modem);
            $this->getPacketName($modem);
            if($modem->invoice_request==$modem->simCard->request_balance){
                $this->parseBalance($modem);
                $this->nextCommand($modem);

            }else{
                if($command=$this->isPendingCommand($modem->serial_number,$modem->invoice_request)){

                    $timePending=new \DateTime($command->pending_at);
                    $timeModemUpdate=new \DateTime($modem->updated_at);
                    echo($timePending->format("Y-m-d-H:i:s").' - '.$timeModemUpdate->format("Y-m-d-H:i:s"));
                    if($timePending<$timeModemUpdate){



                        $command->disabled_at=date("Y-m-d H:i:s");
                        $command->status="DISABLED";
                        $command->save();
                        $this->nextCommand($modem);
                    }

                }else{
                    $this->nextCommand($modem);
                }
            }

        }

    }


}