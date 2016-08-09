<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;
use app\models\AlertsList;
use yii\helpers\Html;
use app\components\Events\EventsText;
use yii\helpers\ArrayHelper;
use Yii;
use app\models\CounterModel;
use app\models\Address;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 14.01.16
 * Time: 14:37
 */
class ModemHandler extends EventHandler
{
    /**
     * @var AlertsList
     */
    public $model;
    public $valueHandlerArray=[];

    public function init(){
        $this->valueHandlerArray = [
            'type' =>$this->model->getModemTypeList(),
            'geo_location_id' => ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress')
        ];
    }

    public function getDescription()
    {

        $this->description .= Yii::t('events','Modem')." " . Html::a('№' . $this->model->serial_number, Yii::$app->urlManager->createUrl(['admin/modem/editmodem', 'serial_number' => $this->model->serial_number,]));
        $this->description .= " ( " .Yii::t('events','Address'). Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/modem/modemslist", 'geo_location_id' => $this->model->geo_location_id])) . ")";

        if (isset($this->model->counters[0])) {
            $this->counter_type = $this->model->counters[0]->type;
            if (isset($this->model->counters[0]->address)) {
                $this->region_id = $this->model->counters[0]->address->region_id;
            }
        }
    }





}