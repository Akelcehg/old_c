<?php

/**
 * User: Igor S <igor.skakovskiy@sferastudios.com>
 * Date: 7/15/15
 * Time: 8:20 AM
 */

namespace app\commands;

use app\components\Alerts\Alerts;
use app\components\Alerts\AlertsHandler;
use yii\console\Controller;
use yii\db\Query;
use Yii;
use app\models\CounterModel;

/**
 * Counter Import Command From TSV File. Change separator if you want use it for different formats like csv or different
 *
 * Class CounterImportController
 * @package app\commands
 */
class AlertInputCommandController extends Controller {
    
    const DAY = 86400;
    const HOUR = 3600;

    /**
     * Main and default command for importing $path support internal or url source path
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param $path
     */
    public function actionIndex() {

        $alertH=new AlertsHandler();
        $alertH->Modem20Check();
    }

    public function actionAllCheck() {

        $alertH=new AlertsHandler();
        $alertH->ModemCheck();
    }

    public function LowBatteryLevelDetect() {

        $modems = (new Query())
            ->select(['type', 'serial_number'])
            ->from('modems')
            ->all();
        foreach ($modems as $modem) {

            if ($modem['type'] == 'built-in') {

                $this->BuiltInDetect($modem['serial_number']);
            } else {

                $this->DiscreteDetect($modem['serial_number']);
            }
        }
    }

    public function BuiltInDetect($serialNumber) {

        $rows = (new Query())
            ->select('serial_number')
            ->from('rmodules')
            ->where('modem_id = :modem_id', [':modem_id' => $serialNumber])
            ->andWhere('battery_level <= :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']])
            ->andWhere('is_ignore_alert != 1')
            ->all();

        if ($rows) {

            AlertsHandler::AddInAlertsList($serialNumber, 'lowBatteryLevel','modem');
        }
    }

    public function DiscreteDetect($serialNumber) {
        $rows = (new Query())
            ->select('serial_number')
            ->from('rmodules')
            ->where('modem_id = :modem_id', [':modem_id' => $serialNumber])
            ->andWhere('battery_level < :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']])
            ->andWhere('is_ignore_alert != 1')
            ->all();

        foreach ($rows as $row) {
            AlertsHandler::AddInAlertsList($row['serial_number'], 'lowBatteryLevel');
        }
    }




    public function LowBatteryLevelDetect20() {

        $modems = (new Query())
                ->select(['type', 'serial_number'])
                ->from('modems')
                ->all();
        foreach ($modems as $modem) {

            if ($modem['type'] == 'built-in') {

                $this->BuiltInDetect20($modem['serial_number']);
            } else {

                $this->DiscreteDetect20($modem['serial_number']);
            }
        }
    }

 

    public function BuiltInDetect20($serialNumber) {

        $rows = (new Query())
                ->select('serial_number')
                ->from('rmodules')
                ->where('modem_id = :modem_id', [':modem_id' => $serialNumber])
                ->andWhere('battery_level < :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']])
                ->andWhere('updated_at > DATE_ADD(NOW(), INTERVAL "-20" MINUTE)')
                ->andWhere('is_ignore_alert != 1')
                ->all();
        if ($rows) {
            AlertsHandler::AddInAlertsList($serialNumber, 'lowBatteryLevel','modem');
        }
    }

    public function BalansDetect() {
        $rows = (new Query())
            ->select('serial_number')
            ->from('modems')
            ->where('balans < 5')
            ->all();

        foreach ($rows as $row) {
            AlertsHandler::AddInAlertsList($row['serial_number'], 'lowBalance');
        }
    }

    public function DiscreteDetect20($serialNumber) {
 $rows = (new Query())
                ->select('serial_number')
                ->from('rmodules')
                ->where('modem_id = :modem_id', [':modem_id' => $serialNumber])
                ->andWhere('battery_level < :battery_level', [':battery_level' => Yii::$app->params['LowBatteryLevel']])
                ->andWhere('updated_at > DATE_ADD(NOW(), INTERVAL "-20" MINUTE)')
                ->andWhere('is_ignore_alert != 1')
                ->all();
 
        foreach ($rows as $row) {
            AlertsHandler::AddInAlertsList($row['serial_number'], 'lowBatteryLevel');
        }
    }

    public function DisconnectDetect() {

        $modems = (new Query())
                ->select(['serial_number','type'])
                ->from('modems')
                ->all();


        print_r($modems);

        foreach ($modems as $oneModem) {

            if (!empty($oneModem['serial_number'])) {
                $counters = (new Query())
                        ->select(['serial_number', 'updated_at', 'update_interval','is_ignore_alert'])
                        ->from('rmodules')
                        ->where('modem_id = :modem_id', [':modem_id' => $oneModem['serial_number']])
                        ->andWhere('serial_number IS NOT NULL')
                        ->andWhere('counter_id IS NOT NULL')
                        ->all();

                foreach ($counters as $oneCounter) {

                    $updatedAt = explode(' ', $oneCounter['updated_at']);
                    $updatedAtDate = explode('-', $updatedAt[0]);
                    $updatedAtTime = explode(':', $updatedAt[1]);
                    $updatedAtInSec = mktime($updatedAtTime[0], $updatedAtTime[1], $updatedAtTime[2], $updatedAtDate[1], $updatedAtDate[2], $updatedAtDate[0]);
                    $periodInSec = time() - $updatedAtInSec;

                    if($oneCounter['is_ignore_alert'] != 1) {
                        if (($periodInSec > (Yii::$app->params['MaxWaitingPeriod'] * AlertInputCommandController::HOUR * $oneCounter['update_interval'])) ) {

                            if($oneModem['type']!='built-in') {

                                AlertsHandler::AddInAlertsList($oneCounter['serial_number'], 'disconnect');
                            }
                            else
                            {

                                AlertsHandler::AddInAlertsList($oneModem['serial_number'], 'disconnect','modem');

                            }
                            // echo $oneCounter['serial_number'].' - '.$periodInSec.' - '.Yii::$app->params['MaxWaitingPeriod'] * AlertInputController::HOUR * $oneCounter['update_interval'].' - '.$oneCounter['updated_at']."\n";
                        } else {

                        }
                    }
                }
            }
        }
    }


}
