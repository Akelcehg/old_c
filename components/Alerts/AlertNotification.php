<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AlertNotification
 *
 * @author alks
 */

namespace app\components\Alerts;
use app\models\CorrectorToCounter;
use app\models\Modem;
use app\models\ModemStatus;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use app\models\Counter;
use app\models\AlertsList;

class AlertNotification extends Component {
    //put your code here
    
     public static function  generateNotification($alertId,$device_type) {
           
        $alert=AlertsList::find()->where(['id'=>$alertId])->one();

         if($device_type=='prom') {
             $counter = CorrectorToCounter::find()->where(['modem_id' => $alert->serial_number])->one();
         }else{
             $counter = Modem::find()->where(['serial_number' => $alert->serial_number])->one();
         }
       
        $content ='Предупреждение!'."<br/> ";
        $content.='Тип :'.Html::a($alert->getAlertTypeText(), Yii::$app->urlManager->createAbsoluteUrl(['/admin/alertinput/editalerts','id'=>$alertId]))."<br/> ";
        
        if(isset($counter->address->fulladdress)){
        $content.='Адресс: '.$counter->address->fulladdress."<br/> ";
        }

       // $content.='Ф.И.О.: '.$counter->fullname."<br/> ";

         if($device_type=='prom') {
             $content.='Ф.И.О.: '.$counter->company."<br/> ";
         }else{
             $content.='Ф.И.О.: '.$counter->counters[0]->fullname."<br/> ";
         }


         if($device_type=='prom') {
             $content .= '№ Модема: ' . $counter->modem_id . "<br/> ";
         }else{
             $content .= '№ Модема: ' . $counter->serial_number . "<br/> ";
         }

        $content.=$alert->created_at;
        $text = Html::tag('div', $content);
        
        return $text;
     }
     
        public static function  generateTelegramNotification($alertId,$device_type) {
           
        $alert=AlertsList::find()->where(['id'=>$alertId])->one();
            if($device_type=='prom') {
                $counter = CorrectorToCounter::find()->where(['modem_id' => $alert->serial_number])->one();
            }else{
                $counter = Modem::find()->where(['serial_number' => $alert->serial_number])->one();
            }
       
        $content ='Предупреждение!'."\n ";
        $content.='Тип :'.$alert->getAlertTypeText()."\n";
        $content.='Ссылка :'.Yii::$app->urlManager->createAbsoluteUrl(['/admin/alertinput/editalerts','id'=>$alertId])."\n ";
        if(isset($counter->address->fulladdress)){
        $content.='Адресс: '.$counter->address->fulladdress."\n ";
        }

            if($device_type=='prom') {
                $content.='Ф.И.О.: '.$counter->company."<br/> ";
            }else{
                $content.='Ф.И.О.: '.$counter->counters[0]->fullname."<br/> ";
            }
                if($device_type=='prom') {

        $content.='№ радиомодуля: '.$counter->modem_id."\n ";

                } else {
                    $content.='№ Модема: '.$counter->serial_number."\n ";
                }
        $content.=$alert->created_at;
        
        
        return $content;
     }


    public static function  generateWidgetNotification($alertId,$device_type) {

        $content='';

        $alert=AlertsList::find()->where(['id'=>$alertId])->one();


        if($device_type=='prom') {
            $counter = ModemStatus::find()->where(['modem_id' => $alert->serial_number])->one();
            if(!empty($counter)) {
                $time = $counter->time_on_line;
                $balance = $counter->balance;

            }else{
                $time = '';
                $balance = '';
            }
        }else{
            $counter = Modem::find()->where(['serial_number' => $alert->serial_number])->one();
            if(!empty($counter)) {
                $time = $counter->updated_at;
                $balance = $counter->balans;
            }else{
                $time = '';
                $balance = '';
            }

        }



        switch($alert->type){
            case 'leak':
                $content.=Yii::t('alerts','Leak');
                break;
            case 'tamper':
                $content.=Yii::t('alerts','Tamper');
                break;
              case 'magnet':
                $content.=Yii::t('alerts','MagnetUsed');//'Использован магнит';
                break;
            case 'disconnect':
                $content.=Yii::t('alerts','LastOnline').': '.$time;//'последний выход на связь: '
                break;
            case 'lowBatteryLevel':
                if(isset($counter->rmodules)) {
                    $content .= Yii::t('alerts','BatteryLevel').':'. $counter->rmodules[0]->battery_level.Yii::t('alerts','recommended').' - '.Yii::$app->params['LowBatteryLevel'];
                    //$content .= 'Заряд батареи:' . $counter->rmodules[0]->battery_level.' рекомендуемый - '.Yii::$app->params['LowBatteryLevel'];
                }

                break;
             case 'lowBalance':
                 $content.=Yii::t('alerts','onAccount').': '.$balance;
               // $content.='на счету: '.$balance;
                break;
        }


        return $content;
    }
    
}
