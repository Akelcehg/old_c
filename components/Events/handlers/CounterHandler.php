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
class CounterHandler extends EventHandler
{
    /**
     * @var AlertsList
     */
    public $model;
    public $valueHandlerArray=[];


    public function init(){
        $this->valueHandlerArray = [
            'type' => $this->model->getCounterTypeList(),
            'user_type' => $this->model->getUserTypeList(),
            'counter_model' => ArrayHelper::map(CounterModel::find()->all(), 'id', 'name'),
            'geo_location_id' => ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress')
        ];
    }


    public function getDescription()
    {

        $this->description.= Yii::t('events','Editing')." ".Yii::t('events','Counter').Html::a(' №' . $this->model->id, Yii::$app->urlManager->createUrl(['/admin/counter/editcounter', 'id' => $this->model->id,]));
        $this->description.=" ( ".Yii::t('events','Modem') . Html::a(' №' . $this->model->modem_id, Yii::$app->urlManager->createUrl(["admin/counter/editmodem", 'serial_number' => $this->model->modem_id]));
        $this->description.= ", " .Yii::t('events','Address'). Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->geo_location_id])) . ")";

        $this->counter_type=$this->model->type;

        if(isset($this->model->address)){
            $this->region_id = $this->model->address->region_id;
        }
    }





}