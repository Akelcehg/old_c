<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendMail
 *
 * @author alks
 */

namespace app\components\Alerts\Notification;

use app\components\Alerts\AlertNotification;
use Yii;
use yii\base\Component;


class TelegramAlertNotification extends Component implements InterfaceAlterNotification {

    public $telegramList = [];
    public $alertId;
    public $device_type;

    public function send() {


        $body = AlertNotification::generateTelegramNotification($this->alertId,$this->device_type);
        foreach ($this->telegramList as $telegram) {
        Yii::$app->bot->sendMessage($telegram, $body);
        }
       
    }

}
