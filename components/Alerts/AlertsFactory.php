<?php
namespace app\components\Alerts;
use app\components\Alerts\ComBit\ComBitAlertsFactory;
use app\components\Alerts\Prom\PromAlertsFactory;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.03.16
 * Time: 16:44
 */
class AlertsFactory
{

    public $type;
    public $alert_type;
    public $modem_id;



    public function createAlert()
    {
        $output=null;

        switch($this->type){
            case "combit" :
                $output=new ComBitAlertsFactory();
                $output->alert_type=$this->alert_type;
                $output->modem_id=$this->modem_id;
                $output->createComBitAlert();
                break;

            case "prom" :
                $output=new PromAlertsFactory();
                $output->alert_type=$this->alert_type;
                $output->modem_id=$this->modem_id;
                $output->createPromAlert();
                break;
        }

        return $output;

    }

}