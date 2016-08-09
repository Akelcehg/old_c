<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 18.05.16
 * Time: 14:09
 */

namespace app\modules\metrix\components;


use app\modules\metrix\models\MetrixCommandConveyor;
use app\modules\metrix\models\MetrixCounter;
use yii\base\Component;

class MetrixCommandGenerator extends Component
{

    const CloseValveCode = [83,00,81,01,253,31,00];
    const OpenValveWithLeakCheckCode =[83,00,81,01,253,31,01];
    const OpenValveWithLeakCheckAndKeyConfirmCode =[83,00,81,01,253,31,02];
    const ForceOpenValveCode=[83,00,81,01,253,31,03];

    public $counter_id;

    public function addMetrixCommand ($command,$commandType){
        $metrix=MetrixCounter::findOne(['id'=>$this->counter_id]);

        $mCC=MetrixCommandConveyor::find()->where(['command'=>$command,'status'=>'ACTIVE'])->one();

        if(empty($mCC)) {
            $metrixCommandConveyor = new MetrixCommandConveyor();
            $metrixCommandConveyor->command_type = $commandType;
            $metrixCommandConveyor->command = $command;
            $metrixCommandConveyor->modem_id = $metrix->modem_id;
            $metrixCommandConveyor->status = 'ACTIVE';
            $metrixCommandConveyor->save();
        }
    }

    public static function GetCloseValveCommand(){
        return self::ArrayToHex(self::getLongFrameArray(self::CloseValveCode));
    }

    public static function GetOpenValveWithLeakCheckCommand(){
        return self::ArrayToHex(self::getLongFrameArray(self::OpenValveWithLeakCheckCode));
    }

    public static function GetOpenValveWithLeakCheckAndKeyConfirmCommand(){
        return self::ArrayToHex(self::getLongFrameArray(self::OpenValveWithLeakCheckAndKeyConfirmCode));
    }

    public static function GetForceOpenValveCommand(){
        return self::ArrayToHex(self::getLongFrameArray(self::ForceOpenValveCode));
    }


    public function OpenValveWithLeakCheck(){

        $command=self::GetOpenValveWithLeakCheckCommand();
        $this->addMetrixCommand($command,2);

    }

    public function OpenValveWithLeakCheckAndKeyConfirm(){

        $command= self::GetOpenValveWithLeakCheckAndKeyConfirmCommand();
        $this->addMetrixCommand($command,2);

    }

    public function ForceOpenValve(){

        $command=self::GetForceOpenValveCommand();
        $this->addMetrixCommand($command,2);

    }

     public function CloseValve(){

        $command=self::GetCloseValveCommand();
        $this->addMetrixCommand($command,2);

    }

    public function getIndications(){

        $command=$this->ArrayToHex($this->getShortFrameArray([91]));
        $this->addMetrixCommand($command,2);

    }

    private static function getShortFrameArray($array){
        $sum = array_sum($array)%256;
        $result=array_merge([10],$array,[00,$sum,22]);
        return $result;
    }


     private static function getLongFrameArray($array){
        $count=count($array);
         $sum = array_sum($array)%256;
         $result=array_merge([104,$count,$count,104],$array,[$sum,22]);
         return $result;
     }

    private static function ArrayToHex($array){
        $result='';
        foreach($array as $one){
            if($one<16){
                $result.='0';
            }
            $result.=$command=sprintf('%x',$one);
        }
        return $result;
    }

    public function changeTimeInterval($time){

        $timeAsHex=unpack("H*", pack("L", $time));
        $this->addMetrixCommand($timeAsHex[1],3);

    }
}