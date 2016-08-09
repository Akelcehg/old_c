<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 22.03.16
 * Time: 11:03
 */

namespace app\components\Alerts;


use app\components\Alerts\Notification\EmailAlertNotification;
use app\components\Alerts\Notification\TelegramAlertNotification;
use app\components\Events\Events;

use app\models\AlertsList;
use app\models\AlertsToUser;
use app\models\CorrectorToCounter;
use app\models\Counter;
use app\models\Modem;
use Yii;
use yii\db\Query;
use yii\web\HttpException;

class AlertsHandler
{

    public static function GetTypesForUser()
    {
        $alertsTypeToUser = AlertsToUser::find()->where(['user_id' => Yii::$app->user->id])->all();


        $array = [];

        foreach ($alertsTypeToUser as $oneAlertsTypeToUser) {


            $array[] = $oneAlertsTypeToUser->type->name;

        }
        return $array;
    }

    public static function AddInAlertsList($serialNumber, $type, $device_type = 'modem')
    {

        $alertListCheck = AlertsList::find()->where(['serial_number' => $serialNumber, 'type' => $type, 'device_type' => $device_type, 'status' => 'ACTIVE'])->all();

        if ($device_type == 'modem') {
            $modem = Modem::findOne(['serial_number' => $serialNumber]);
        }

        if ($device_type == 'prom') {
            $modem = CorrectorToCounter::findOne(['modem_id' => $serialNumber]);
        }

        if (!isset($alertListCheck[0]) and (isset($modem)) and (isset($modem->address)) and ($modem->address->status == "ACTIVE")) {

            $alertsList = new AlertsList();
            $alertsList->type = $type;
            $alertsList->device_type = $device_type;
            $alertsList->serial_number = $serialNumber;
            $alertsList->status = 'ACTIVE';

            if ($alertsList->save()) {

                if (Yii::$app->params['EmailAlertNotificationEnabled']) {
                    $email = new EmailAlertNotification();
                    $email->mailList = AlertsNotificationUsersArray::MailArrayGenerate($type);
                    $email->alertId = $alertsList->id;
                    $email->device_type = $device_type;
                    $email->send();

                }

                if (Yii::$app->params['TelegramAlertNotificationEnabled']) {
                    $telegram = new TelegramAlertNotification();
                    $telegram->telegramList = AlertsNotificationUsersArray::TelegramArrayGenerate($type);
                    $telegram->alertId = $alertsList->id;
                    $telegram->device_type = $device_type;
                    $telegram->send();

                }

                $alert = new AlertsList();
                $alert->type = $type;

                $eventsNew = new Events();
                $eventsNew->type = 'add';
                $eventsNew->model = $alertsList;
                $eventsNew->newAttributes = $alertsList->getAttributes();
                $eventsNew->AddEvent();

                return true;
            } else {
                print_r($alertsList->getErrors());
                return false;
            }
        }
    }


    public function BalansDetect()
    {
        $this->BalansComBitDetect();
        $this->BalansPromDetect();
        $this->BalansComBitAutoDisable();
        $this->BalansPromAutoDisable();
    }

    public function BalansComBitDetect()
    {
        $rows = (new Query())
            ->select('serial_number')
            ->from('modems')
            ->where('balans < :balans', [':balans' => Yii::$app->params['MinimumBalanceLevel']])
            ->all();

        foreach ($rows as $row) {

            $alert = new Alerts();
            $alert->alert_type = 'lowBalance';
            $alert->type = "combit";
            $alert->modem_id = $row['serial_number'];
            $alert->CreateAlert();

        }
    }


    public function BalansComBitAutoDisable($time = null)
    {

        $rows = (new Query())
            ->select('alerts_list.id')
            ->from('alerts_list')
            ->leftJoin('modems', 'alerts_list.serial_number=modems.serial_number')
            ->where(['alerts_list.type' => 'lowBalance'])
            ->andWhere(['alerts_list.status' => 'ACTIVE'])
            ->andWhere(['alerts_list.device_type' => 'modem'])
            ->andWhere('modems.balans >= :balans', [':balans' => Yii::$app->params['MinimumBalanceLevel']]);

        $rows = $rows->all();

        $this->DisableAlerts($rows);
    }

    public function BalansPromDetect()
    {
        $rows = (new Query())
            ->select('modem_id')
            ->from('modem_status')
            ->where('balance < :balance', [':balance' => Yii::$app->params['MinimumBalanceLevel']])
            ->all();

        foreach ($rows as $row) {

            $alert = new Alerts();
            $alert->alert_type = 'lowBalance';
            $alert->type = "prom";
            $alert->modem_id = $row['modem_id'];
            $alert->CreateAlert();

        }
    }

    public function BalansPromAutoDisable($time = null)
    {

        $rows = (new Query())
            ->select('alerts_list.id')
            ->from('alerts_list')
            ->leftJoin('modem_status', 'alerts_list.serial_number=modem_status.modem_id')
            ->where(['alerts_list.type' => 'lowBalance'])
            ->andWhere(['alerts_list.status' => 'ACTIVE'])
            ->andWhere(['alerts_list.device_type' => 'prom'])
            ->andWhere('modem_status.balance >= :balance', [':balance' => Yii::$app->params['MinimumBalanceLevel']]);

        $rows = $rows->all();

        $this->DisableAlerts($rows);
    }


    public function LowLevelBatteryDetect($time = null)
    {
        // DATE_ADD(NOW(), INTERVAL "-20" MINUTE)
        $rows = (new Query())
            ->select('modems.serial_number')
            ->from('modems')
            ->leftJoin('rmodules', 'modems.serial_number=rmodules.modem_id')
            ->where('battery_level < :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']])
            ->andFilterWhere(['<', 'modems.updated_at', $time])
            ->andWhere('is_ignore_alert != 1')
            ->andWhere('rmodules.last_impulse != 0')
            ->groupBy('modems.serial_number')
            ->all();

        foreach ($rows as $row) {


            $alert = new Alerts();
            $alert->alert_type = 'lowBatteryLevel';
            $alert->type = "combit";
            $alert->modem_id = $row['serial_number'];
            $alert->CreateAlert();
        }
    }

    public function LowLevelBatteryAutoDisable($time = null)
    {

        $rows = (new Query())
            ->select('alerts_list.id')
            ->from('alerts_list')
            ->leftJoin('modems', 'alerts_list.serial_number=modems.serial_number')
            ->leftJoin('rmodules', 'rmodules.modem_id=modems.serial_number')
            ->where(['alerts_list.type' => 'lowBatteryLevel'])
            ->andWhere(['alerts_list.status' => 'ACTIVE'])
            ->andWhere(['alerts_list.device_type' => 'modem'])
            ->andWhere('rmodules.battery_level >= :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']]);

        $rows = $rows->all();

        $this->DisableAlerts($rows);
    }

    private function DisableAlerts($rows = [])
    {


        foreach ($rows as $row) {

            $alert = AlertsList::find()
                ->where(['id' => $row['id']])
                ->one();
            $alert->status = 'DEACTIVATED';
            if($alert->save()) {

                $eventsNew = new Events();
                $eventsNew->type = 'autoDisable';
                $eventsNew->model = $alert;
                $eventsNew->newAttributes = $alert->getAttributes();
                $eventsNew->AddEvent();
            }

        }
    }

    public function LowLevelBatteryDetect20()
    {
        $this->LowLevelBatteryDetect('DATE_SUB(NOW(), INTERVAL 20 MINUTE)');
        $this->LowLevelBatteryAutoDisable();
    }


    public function DisconnectComBitDetect()
    {
        /*
        SELECT * FROM `modems`
        LEFT JOIN `rmodules` ON `modems`.`serial_number`=`rmodules`.`modem_id`
        WHERE `modems`.`updated_at`< DATE_ADD(NOW(),INTERVAL CONCAT_WS("-",`rmodules`.`update_interval`*1) HOUR)
        GROUP BY `modems`.`serial_number`
        */

        $rows = (new Query())
            ->select('modems.serial_number')
            ->from('modems')
            ->leftJoin('rmodules', 'modems.serial_number=rmodules.modem_id')
            ->where('modems.updated_at < DATE_SUB(NOW(),INTERVAL `rmodules`.`update_interval`*:mvp HOUR)', [':mvp' => Yii::$app->params['MaxWaitingPeriod']])
            ->andWhere('is_ignore_alert != 1')
            ->groupBy('modems.serial_number');

        $rows = $rows->all();

        foreach ($rows as $row) {

            $alert = new Alerts();
            $alert->alert_type = 'disconnect';
            $alert->type = "combit";
            $alert->modem_id = $row['serial_number'];
            $alert->CreateAlert();
        }


    }

    public function DisconnectComBitAutoDisable()
    {
        /*
        SELECT * FROM `modems`
        LEFT JOIN `rmodules` ON `modems`.`serial_number`=`rmodules`.`modem_id`
        WHERE `modems`.`updated_at`< DATE_ADD(NOW(),INTERVAL CONCAT_WS("-",`rmodules`.`update_interval`*1) HOUR)
        GROUP BY `modems`.`serial_number`
        */

        $rows = (new Query())
            ->select('alerts_list.id')
            ->from('alerts_list')
            ->leftJoin('modems', 'alerts_list.serial_number=modems.serial_number')
            ->leftJoin('rmodules', 'rmodules.modem_id=modems.serial_number')
            ->where(['alerts_list.type' => 'disconnect'])
            ->andWhere(['alerts_list.status' => 'ACTIVE'])
            ->andWhere(['alerts_list.device_type' => 'modem'])
            ->andWhere('modems.updated_at > DATE_SUB(NOW(),INTERVAL `rmodules`.`update_interval`*:mvp HOUR)', [':mvp' => Yii::$app->params['MaxWaitingPeriod']])
            ->andWhere('rmodules.updated_at > DATE_SUB(NOW(),INTERVAL `rmodules`.`update_interval`*:mvp HOUR)', [':mvp' => Yii::$app->params['MaxWaitingPeriod']]);



        $rows = $rows->all();

        $this->DisableAlerts($rows);


    }


    public function DisconnectPromDetect($time = null)
    {

        $rows = (new Query())
            ->select('modem_id')
            ->from('modem_status')
            ->where('time_on_line < DATE_SUB(NOW(),INTERVAL 1 HOUR)')
            ->all();


        foreach ($rows as $row) {


            $alert = new Alerts();
            $alert->alert_type = 'disconnect';
            $alert->type = "prom";
            $alert->modem_id = $row['modem_id'];
            $alert->CreateAlert();
        }


    }

    public function DisconnectPromAutoDisable($time = null)
    {

        $rows = (new Query())
            ->select('alerts_list.id')
            ->from('alerts_list')
            ->leftJoin('modem_status', 'alerts_list.serial_number=modem_status.modem_id')
            ->where(['alerts_list.type' => 'disconnect'])
            ->andWhere(['alerts_list.status' => 'ACTIVE'])
            ->andWhere(['alerts_list.device_type' => 'prom'])
            ->andWhere('time_on_line > DATE_SUB(NOW(),INTERVAL 1 HOUR)');



        $rows = $rows->all();



        $this->DisableAlerts($rows);
    }

    public function DisconnectDetect()
    {
        $this->DisconnectComBitDetect();
        $this->DisconnectPromDetect();
        $this->DisconnectComBitAutoDisable();
        $this->DisconnectPromAutoDisable();
    }

    public function Modem20Check()
    {

        $this->BalansDetect();
        $this->LowLevelBatteryDetect20();
        $this->DisconnectDetect();

    }

    public function ModemCheck()
    {

        $this->BalansDetect();
        $this->LowLevelBatteryDetect();
        $this->LowLevelBatteryAutoDisable();
        $this->DisconnectDetect();

    }

    public function EditAlerts()
    {
        $id = Yii::$app->request->get('id');
        $backUrl = Yii::$app->request->get('backUrl', 'index');
        $alertData = Yii::$app->request->post('AlertsList', false);
        $alert = AlertsList::find()->where(['id' => $id])->one();

        if (!$alert) {
            throw new HttpException(404, 'Предупреждение не найдено');
        };

        if ($alertData) {

            $alert->setAttributes($alertData, false);

            $events = new Events();
            $events->oldAttributes = $alert->getOldAttributes();

            if ($alert->save()) {

                $currentUser = new \app\models\User();
                $events->newAttributes = $alert->getAttributes();
                $events->model = $alert;
                //$events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
                $events->type = 'edit';
                //$events->description = 'Редактирование Предупреждения №' . $id;
                $events->AddEvent();

                //Events::AddEvent('alert','Редактирование Предупреждения №'.$id,$alert);
                return Yii::$app->controller->redirect($backUrl);
            }
        }


        return $alert;

    }

}