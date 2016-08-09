<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;
use app\models\AlertsList;
use yii\helpers\Html;
use app\components\Events\EventsText;
use Yii;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 14.01.16
 * Time: 14:37
 */
class AlertHandler extends EventHandler
{
    /**
     * @var AlertsList
     */
    public $model;
    public $valueHandlerArray=[];


    public function init(){
        $this->valueHandlerArray = [
            'type' =>$this->model->getAlertTypeList(),
            'status' =>$this->model->getAllStatuses(),
            'device_type'=>$this->model->getAlertDeviceTypeList()
        ];
    }



    public function getDescription()
    {

        $this->description .= "Предупреждения " . Html::a('№' . $this->model->id, Yii::$app->urlManager->createUrl(['admin/alertinput/editalerts', 'id' => $this->model->id,]));
        $this->description.=' ' . $this->model->getAlertTypeText().'<br/>';
        $this->description.=" (  счетчик" . Html::a('№' . $this->model->counter->id, Yii::$app->urlManager->createUrl(['/admin/counter/editcounter', 'serial_number' => $this->model->counter->id,]));
        $this->description.= " , модем " . Html::a('№' . $this->model->counter->modem_id, Yii::$app->urlManager->createUrl(["admin/modem/editmodem", 'serial_number' => $this->model->counter->modem_id]));
        $this->description.= ", адресс " . Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->counter->geo_location_id])).")";

        $this->counter_type=$this->model->counter->type;

        if($this->model->counter->geo_location_id!=0){
            $this->region_id = $this->model->counter->address->region_id;
        }
    }





}