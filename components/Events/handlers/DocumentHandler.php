<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;
use app\models\Address;
use app\models\AlertsList;
use app\models\Counter;
use app\models\CounterModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\Events\EventsText;
use Yii;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 14.01.16
 * Time: 14:37
 */
class DocumentHandler extends EventHandler
{
    /**
     * @var Address
     */
    public $model;
    public $valueHandlerArray=[];


    public function init(){
        $this->valueHandlerArray = [
            'address_id' => ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'),
            'type'=>Counter::getCounterTypeList(),
            'counter_model_id'=>ArrayHelper::map(CounterModel::find()->all(), 'id', 'name'),

        ];
    }

    public function getDescription()
    {

        $this->description .= Yii::t('events','Documents') . Html::a('№' . $this->model->id , Yii::$app->urlManager->createUrl(["admin/documents/update", 'id' => $this->model->id]));

    }





}