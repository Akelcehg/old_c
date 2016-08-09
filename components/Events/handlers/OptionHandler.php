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
class OptionHandler extends EventHandler
{
    /**
     * @var Rmodule
     */
    public $model;
    public $valueHandlerArray=[];

    public function init(){

    }


    public function getDescription()
    {
        $this->description .= Yii::t('events','OnOffRtMode');
    }


}