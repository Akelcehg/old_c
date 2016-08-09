<?php
namespace app\components\Alerts\Prom;
use app\components\Alerts;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.01.16
 * Time: 15:51
 */
class PromAlertsFactory
{

    public $alert_type;
    public $modem_id;

    public function createPromAlert()
    {
        switch($this->alert_type) {

            case "disconnect" :
                Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type,'prom');
                break;

            case "lowBalance" :
                Alerts\AlertsHandler::AddInAlertsList($this->modem_id, $this->alert_type,'prom');
                break;

        }

    }


}