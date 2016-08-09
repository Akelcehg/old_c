<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;
use app\models\AlertsList;
use app\models\Rmodule;
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
class RmoduleHandler extends EventHandler
{
    /**
     * @var Rmodule
     */
    public $model;
    public $valueHandlerArray=[];

    public function init(){
        $this->valueHandlerArray = [

            'geo_location_id' => ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'),
            'is_ignore_alert'=>$this->model->IsIgnoreAlertsList(),
        ];
    }

    public function getDescription()
    {

        $this->description .= Yii::t('events','Rmodule') .Html::a('№' . $this->model->id, Yii::$app->urlManager->createUrl(['/admin/rmodule/editrmodule', 'serial_number' => $this->model->serial_number,]));
        $this->description .= "(".Yii::t('events','Modem') . Html::a('№' . $this->model->modem_id, Yii::$app->urlManager->createUrl(["admin/modem/editmodem", 'serial_number' => $this->model->modem_id]));
        $this->description .= " ".Yii::t('events','Counter') . Html::a('№' . $this->model->counter_id, Yii::$app->urlManager->createUrl(["admin/counter/editcounter", 'id' => $this->model->counter_id]));
        $this->description .=", " .Yii::t('events','Address'). Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->geo_location_id])) . ")";

        if(isset($this->model->counter)) {
            $this->counter_type = $this->model->counter->type;
        }
        if(isset($this->model->address)) {
            $this->region_id = $this->model->address->region_id;
        }

    }





}