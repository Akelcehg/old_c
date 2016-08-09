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

namespace app\components;
use app\models\Modem;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use app\models\Counter;
use app\models\AlertsList;

class AlertNotification extends Component {
    //put your code here
    
     public static function  generateNotification($alertId,$device_type) {
           
        $alert=AlertsList::find()->where(['id'=>$alertId])->one();

         if($device_type=='counter') {
             $counter = Counter::find()->where(['rmodule_id' => $alert->serial_number])->one();
         }else{
             $counter = Modem::find()->where(['serial_number' => $alert->serial_number])->one();
         }
       
        $content ='Предупреждение!'."<br/> ";
        $content.='Тип :'.Html::a($alert->getAlertTypeText(), Yii::$app->urlManager->createAbsoluteUrl(['/admin/alertinput/editalerts','id'=>$alertId]))."<br/> ";
        
        if(isset($counter->address->fulladdress)){
        $content.='Адресс: '.$counter->address->fulladdress."<br/> ";
        }

       // $content.='Ф.И.О.: '.$counter->fullname."<br/> ";

         if($device_type=='counter') {
             $content.='Ф.И.О.: '.$counter->fullname."<br/> ";
         }else{
             $content.='Ф.И.О.: '.$counter->counters[0]->fullname."<br/> ";
         }


         if($device_type=='counter') {
             $content .= '№ радиомодуля: ' . $counter->serial_number . "<br/> ";
         }else{
             $content .= '№ Модема: ' . $counter->serial_number . "<br/> ";
         }

        $content.=$alert->created_at;
        $text = Html::tag('div', $content);
        
        return $text;
     }
     
        public static function  generateTelegramNotification($alertId,$device_type) {
           
        $alert=AlertsList::find()->where(['id'=>$alertId])->one();
            if($device_type=='counter') {
                $counter = Counter::find()->where(['rmodule_id' => $alert->serial_number])->one();
            }else{
                $counter = Modem::find()->where(['serial_number' => $alert->serial_number])->one();
            }
       
        $content ='Предупреждение!'."\n ";
        $content.='Тип :'.$alert->getAlertTypeText()."\n";
        $content.='Ссылка :'.Yii::$app->urlManager->createAbsoluteUrl(['/admin/alertinput/editalerts','id'=>$alertId])."\n ";
        if(isset($counter->address->fulladdress)){
        $content.='Адресс: '.$counter->address->fulladdress."\n ";
        }

            if($device_type=='counter') {
                $content.='Ф.И.О.: '.$counter->fullname."<br/> ";
            }else{
                $content.='Ф.И.О.: '.$counter->counters[0]->fullname."<br/> ";
            }
                if($device_type=='counter') {

        $content.='№ радиомодуля: '.$counter->serial_number."\n ";

                } else {
                    $content.='№ Модема: '.$counter->serial_number."\n ";
                }
        $content.=$alert->created_at;
        
        
        return $content;
     }
    
}
