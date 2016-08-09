<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alerts
 *
    }
 * @author alks
 */

namespace app\components;

use app\components\Alerts\Notification\EmailAlertNotification;
use app\components\Alerts\Notification\TelegramAlertNotification;
use app\models\Modem;
use yii\base\Component;
use app\models\Modems;
use app\models\AlertsList;
use app\models\CounterAddress;
use app\models\User;
use Yii;
use app\models\TelegramToUser;
use app\components\Events;

class Alerts extends Component {

    //put your code here

    const DAY = 86400;
    const HOUR = 3600;

    // UserCounter object; 
    public $query;
    public $modemBuf = [];

    public function __construct($query) {

        $this->query = $query;
        parent::__construct();
    }



    public function isLowBatteryLevel($counter) {

        if (isset($counter->modem)) {
            if ($counter->modem->type == 'discrete') {

                if($counter->rmodule) {

                    if ($counter->rmodule->battery_level <= Yii::$app->params['LowBatteryLevel']) {

                        return true;
                    } else {

                        return false;
                    }
                }
            } else {
                if (($counter->rmodule)and($counter->rmodule->battery_level <= Yii::$app->params['LowBatteryLevel']) and ( !in_array($counter->modem->serial_number, $this->modemBuf)) and ( $counter->rmodule->is_ignore_alert != 1)) {
                    $this->modemBuf[] = $counter->modem->serial_number;

                    return true;
                } else {

                    return false;
                }
            }
        }

        /* if ($counter->battery_level <= Yii::$app->params['LowBatteryLevel']){
          if ($counter->battery_level <= Yii::$app->params['LowBatteryLevel'] and !in_array($counter->modem->serial_number, $this->modemBuf) and $counter->is_ignore_alert !=1) {
          $this->modemBuf[]=$counter->modem->serial_number;
          return true;
          } else {
          return false;
          } */
    }

    public function isDisconnect($counter) {



        $userModem = Modem::find()->where(['serial_number' => $counter->modem_id])->one();



        if ($userModem and $counter->rmodule) {

            $updatedAt = explode(' ', $userModem->updated_at);
            $updatedAtDate = explode('-', $updatedAt[0]);
            $updatedAtTime = explode(':', $updatedAt[1]);
            $updatedAtInSec = mktime($updatedAtTime[0], $updatedAtTime[1], $updatedAtTime[2], $updatedAtDate[1], $updatedAtDate[2], $updatedAtDate[0]);
            $periodInSec = time() - $updatedAtInSec;

            if ($periodInSec > Yii::$app->params['MaxWaitingPeriod'] * Alerts::DAY and $counter->rmodule->is_ignore_alert != 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function getAlerts($counter) {

        $output = [
            'lowBatteryLevel' => $this->isLowBatteryLevel($counter),
            'disconnect' => $this->isDisconnect($counter)
        ];

        return $output;
    }
    
    public function getAlertsNew($counter) {
    $output=[];
        $alerts = $counter->alerts;
        
        foreach ($alerts as $oneAlert) {
            
            if($oneAlert->status=='ACTIVE'){
            $output[$oneAlert->type]=true;}
        }
        
        return $output;
    }

    public function getAllAlerts() {
        $alertsArr = [
            'leak' => 0,
            'magnet' => 0,
            'tamper' => 0,
            'lowBatteryLevel' => 0,
            'lowBatteryLevelModem' => 0,
            'disconnect' => 0,
        ];



        if ($this->query) {

            $this->modemBuf = [];
            foreach ($this->query as $counter) {

                if ($this->isLowBatteryLevel($counter)) {
                    $alertsArr['lowBatteryLevel'] ++;
                }

                if ($this->isDisconnect($counter)) {
                    $alertsArr['disconnect'] ++;
                }

            }
        }


        return $alertsArr;
    }

    public function getAllJsAlerts() {


        $jsArr = [
            'leak' => '',
            'magnet' => '',
            'tamper' => '',
            'lowBatteryLevel' => '',
            'lowBatteryLevelModem' => '',
            'disconnect' => '',
        ];

        if ($this->query) {
            foreach ($this->query as $counter) {

                if ($this->isLeak($counter)) {

                    $jsArr['leak'].=$counter->id . ',';
                }

                if ($this->isMagnet($counter)) {

                    $jsArr['magnet'].=$counter->id . ',';
                }

                if ($this->isTamper($counter)) {

                    $jsArr['tamper'].=$counter->id . ',';
                }

                if ($this->isLowBatteryLevel($counter)) {

                    $jsArr['lowBatteryLevel'].= $counter->id . ',';
                    $jsArr['lowBatteryLevelModem'] = implode(',', $this->modemBuf);
                }

                // if ($this->isLowBatteryLevel($counter)) {
                //}

                if ($this->isDisconnect($counter)) {

                    $jsArr['disconnect'].=$counter->id . ',';
                }
            }
        }


        return $jsArr;
    }

    public static function AddInAlertsList($serialNumber, $type, $device_type='modem') {

        $alertListCheck = AlertsList::find()->where(['serial_number' => $serialNumber, 'type' => $type, 'device_type'=>$device_type, 'status' => 'ACTIVE'])->all();

        if (!isset($alertListCheck[0])) {

            $alertsList = new AlertsList();
            $alertsList->type = $type;
            $alertsList->device_type =$device_type;
            $alertsList->serial_number = $serialNumber;
            $alertsList->status = 'ACTIVE';

            if ($alertsList->save()) {
                
                if(Yii::$app->params['EmailAlertNotificationEnabled']){

                    $email = new EmailAlertNotification();
                    $email->mailList = Alerts::MailArrayGenerate($type);
                    $email->alertId = $alertsList->id;
                    $email->device_type=$device_type;
                    $email->send();
                
                }

                if(Yii::$app->params['TelegramAlertNotificationEnabled']){

                    $telegram = new TelegramAlertNotification();
                    $telegram->telegramList = Alerts::TelegramArrayGenerate($type);
                    $telegram ->alertId = $alertsList->id;
                    $telegram->device_type=$device_type;
                    $telegram->send();
                 
                }
                
                $alert = new AlertsList();
                $alert->type =$type;
                
                
               /* $events = new Events();
                $events->type = 'alert';
                $events->model= $alertsList;
                //$events->description = 'Приём Предупреждения-'.$alert->getAlertTypeText().' № счетчика'.$serialNumber;
                $events->AddEvent();*/

                $eventsNew= new Events\Events();
                $eventsNew->type='add';
                $eventsNew->model=$alertsList;
                $eventsNew->newAttributes=$alertsList->getAttributes();
                $eventsNew->AddEvent();
                
                
                //Events::AddEvent('alert','Принятие Предупреждения-'.$type.' № счетчика'.$serialNumber,$alertsList);
                
                return true;
            } else {
                print_r($alertsList->getErrors());
                return false;
            }
        }
    }

    public function getAllAlertsNew() {

        $output = [
            'leak' => 0,
            'magnet' => 0,
            'tamper' => 0,
            'lowBatteryLevel' => 0,
            'lowBalance' => 0,
            'disconnect' => 0,
        ];

        $alerts = AlertsList::find()->where(['status' => 'ACTIVE'])->joinWith('counter');



        if ($this->isRole('user')) {
            $alerts->where(['user_id' => Yii::$app->user->id]);
        }

        if ($this->isRole('gasWatcher')) {
           // $alerts->andWhere(['counters.type' => 'gas']);
        }

        if ($this->isRole('waterWatcher')) {
           // $alerts->andWhere(['counters.type' => 'water']);
        }

        if ($this->isRole('regionWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();

            $geoIdList = Address::find()->select('id')->where(['region_id' => $user->geo_location_id])->asArray();

            $alerts->andWhere(['in', 'geo_location_id', $geoIdList]);
        }
        $alerts = $alerts->all();

        foreach ($alerts as $oneAlert) {

            $output[$oneAlert->type] ++;
        }
        return $output;
    }

    public function getAllJsAlertsNew() {


        $jsArr = [
            'leak' => '',
            'magnet' => '',
            'tamper' => '',
            'lowBatteryLevel' => '',
            'lowBatteryLevelModem' => '',
            'lowBalance' => '',
            'disconnect' => '',
        ];

        $alerts = AlertsList::find()->where(['status' => 'ACTIVE'])->joinWith('counter');

        if ($this->isRole('user')) {
            $alerts->where(['user_id' => Yii::$app->user->id]);
        }

        if ($this->isRole('gasWatcher')) {
            $alerts->andWhere(['counters.type' => 'gas']);
        }

        if ($this->isRole('waterWatcher')) {
            $alerts->andWhere(['counters.type' => 'water']);
        }

        if ($this->isRole('regionWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();

            $geoIdList = CounterAddress::find()->select('id')->where(['region_id' => $user->geo_location_id])->asArray();

            $alerts->andWhere(['in', 'geo_location_id', $geoIdList]);
        }

        $alerts = $alerts->all();

        foreach ($alerts as $oneAlert) {

            if($oneAlert->counter) {
                $modem = Modem::find()->where(['serial_number' => $oneAlert->counter->modem_id])->one();

                if ($modem) {
                    if ($oneAlert->type == 'lowBatteryLevel') {
                        if ($modem->type == 'built-in') {
                            $jsArr['lowBatteryLevelModem'] .= $modem->serial_number . ',';
                        } else {
                            $jsArr[$oneAlert->type] .= $oneAlert->counter->id . ',';
                        }
                    } else {
                        $jsArr[$oneAlert->type] .= $oneAlert->counter->id . ',';
                    }


                }
            }

        }



        return $jsArr;
    }

    public static function isRole($role) {
        return array_key_exists($role, Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
    }
    
     public static function MailArrayGenerate($type)
             {
                $mailArray=[];
                $users= User::find()->where(['email_notification_enable'=>'1'])->all();
                
                foreach ($users as $user) {
                    $alertsToUser=  \app\models\AlertsToUser::find()->where(['user_id'=>$user->id])->all();
                    $typeArray=[];
                    foreach ($alertsToUser as $alert) {
                        $typeA= \app\models\AlertsTypes::findOne(['id'=>$alert->alerts_type_id]);
                        $typeArray[]=$typeA['name'];
                    }
                    
                    if(in_array($type,$typeArray))
                    {
                        $mailArray[]=$user->email;
                    }
                    
                    
                }
         
                return $mailArray;
             }
             
              public static function TelegramArrayGenerate($type)
             {
                $telegramArray=[];
                $users= User::find()->where(['telegram_notification_enable' => '1'])->all();
                
                foreach ($users as $user) {
                    $alertsToUser=  \app\models\AlertsToUser::find()->where(['user_id'=>$user->id])->all();
                    $typeArray=[];
                    foreach ($alertsToUser as $alert) {
                        $typeA= \app\models\AlertsTypes::findOne(['id'=>$alert->alerts_type_id]);
                        $typeArray[]=$typeA['name'];
                    }
                    
                    if(in_array($type,$typeArray))
                    {
                        $telToUser= TelegramToUser::findOne(['user_id'=>$user->id]);
                        if(isset( $telToUser->telegram_id )){
                        $telegramArray[]=$telToUser->telegram_id;
                        }
                        
                    }
                    
                    
                }
         
                return $telegramArray;
             }

}
