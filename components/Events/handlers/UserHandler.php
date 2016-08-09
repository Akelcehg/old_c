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
class UserHandler extends EventHandler
{
    /**
     * @var Rmodule
     */
    public $model;
    public $valueHandlerArray=[];

    public function init(){
        $this->valueHandlerArray = [

            'user_type' => $this->model->getUserTypeList(),
            'status' =>  $this->model->getAllStatuses(),
            'geo_location_id' => ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'),

        ];
    }

    public function Handle()
    {
        $this->init();

        switch($this->type){
            case 'add':
                $this->AddEventNotification();
                break;
            case 'edit':
                $this->EditEventNotification();
                break;
            case 'login':
                $this->LoginEventNotification();
                break;
            case 'logout':
                $this->LogoutEventNotification();
                break;
            case 'route':
                $this->RouteEventNotification();
                break;


        }

    }

    public function LoginEventNotification()
    {
        $this->description = " <b>".Yii::t('events','Login')."</b>   ".Yii::t('events','User2').": " . $this->model->fullname . " ip:" .Yii::$app->getRequest()->getUserIP() ;
        $this->save();

    }

    public function LogoutEventNotification()
    {
        if($this->model->lastUserLogin) {
            $this->description = " <b>".Yii::t('events','Logout')."</b>  ".Yii::t('events','User2').": " . $this->model->fullname . " <br> <b> ".Yii::t('events','Login')."</b> : " . $this->model->lastUserLogin;
            $this->save();
        }

    }

    public function RouteEventNotification()
    {
        $this->description = Yii::t('events','User2').":" . $this->model->fullname
            . "<br> ip:" .Yii::$app->getRequest()->getUserIP()
            ." <br> ".Yii::t('events','WentOnPage'). Yii::$app->request->getAbsoluteUrl();
        $this->save();

    }

    public function getDescription()
    {
        $this->description .= Yii::t('events','User3') . Html::a('№' . $this->model->id . "(" . $this->model->fullname . ")", Yii::$app->urlManager->createUrl(["admin/users/edituser", 'id' => $this->model->id,]));
    }





}