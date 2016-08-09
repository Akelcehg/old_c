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


class EmailAlertNotification extends Component implements InterfaceAlterNotification {

    public $mailList = [];
    public $alertId;
    public $device_type;

    public function send() {


        $body = AlertNotification::generateNotification($this->alertId,$this->device_type);

        foreach ($this->mailList as $email) {

            Yii::$app->mail->compose()
                    ->setTo($email)
                    ->setFrom([Yii::$app->params['adminEmail']])
                    ->setSubject('Предупреждение!! ' )
                    ->setHtmlBody($body)
                    ->send();
            sleep(2);
          
        }
    }

}
