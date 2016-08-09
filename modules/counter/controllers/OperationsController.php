<?php

namespace app\modules\counter\controllers;

use app\components\ChartCalc;
use app\models\SimCard;
use app\modules\admin\components\CounterEvents;
use app\modules\metrix\components\MetrixCommandGenerator;
use app\modules\metrix\components\MetrixValveButtonWidget;
use app\modules\metrix\models\MetrixCounter;
use app\modules\metrix\models\MetrixSimCard;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use Yii;

class OperationsController extends Controller
{
    public $layout = 'smartAdminN';


    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @see http://www.yiiframework.com/wiki/65/how-to-setup-rbac-with-a-php-file/
     * http://www.yiiframework.com/wiki/253/cphpauthmanager-how-it-works-and-when-to-use-it/
     *
     * @return array access control rules
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => [
                                'ajaxcountertocalendar',
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['admin', 'PromAdmin'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionAjaxcountertocalendar()
    {
        $counterEvents=new CounterEvents();
        Yii::$app->response->format = 'json';
        return $counterEvents->CounterToCalendar();
    }
}