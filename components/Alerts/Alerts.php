<?php
namespace app\components\Alerts;
use app\components\Events\handlers\AlertHandler;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.01.16
 * Time: 15:15
 */
class Alerts extends \yii\base\Component
{

    public $type;
    public $alert_type;
    public $modem_id;

    /**
     * @var AlertHandler;
     */
    public $alertHandler;


    public function CreateAlert(){

        $alert =new AlertsFactory();
        $alert->alert_type=$this->alert_type;
        $alert->type=$this->type;
        $alert->modem_id=$this->modem_id;
        $alert->createAlert();

    }

}