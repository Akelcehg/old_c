<?php
namespace app\components\Alerts\ComBit;
use app\components\Alerts;
use app\models\AlertsList;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.03.16
 * Time: 16:44
 */
class ComBitAlertsFactory
{

    public $alert_type;
    public $modem_id;

    public function createComBitAlert()
    {

         switch($this->alert_type) {

             case "leak" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;

             case "tamper" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;

             case "magnet" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;

             case "lowLevelBattery" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;

             case "disconnect" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;

             case "lowBalance" :
                 Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type);
                 break;
         }

    }

}