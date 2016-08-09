<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;
use app\models\Address;
use app\models\AlertsList;
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
class AddressHandler extends EventHandler
{
    /**
     * @var Address
     */
    public $model;
    public $valueHandlerArray=[];


    public function init(){
        $this->valueHandlerArray = [
            'status' => $this->model->getAllStatuses(),
            'region_id' => ArrayHelper::map(\app\models\Region::find()->all(), 'id', 'FullRegionName'),
        ];
    }

    public function getDescription()
    {

        $this->description .= Yii::t('events','Address2'). Html::a('№' . $this->model->id . "(" . $this->model->fulladdress . ")", Yii::$app->urlManager->createUrl(["admin/address/editaddress", 'id' => $this->model->id]));

    }





}