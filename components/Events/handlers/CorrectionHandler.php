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
class CorrectionHandler extends EventHandler
{
    /**
     * @var Address
     */
    public $model;
    public $valueHandlerArray=[];


    public function init(){}


    public function getDescription()
    {

        $this->description .= Yii::t('events','Correction2') . Html::a('№' . $this->model->id);

    }





}