<?php

namespace app\modules\prom\controllers;


use app\components\ChartCalc;
use app\components\FlouTechCommandGenerator;
use app\models\CommandConveyor;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use app\models\DayData;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\HourData;
use app\models\Intervention;
use app\models\MomentData;
use app\models\SimCard;
use app\modules\prom\components\DiagnosticWidget;
use app\modules\prom\components\EmergencySituationWidget;
use app\modules\prom\components\ForcedPaymentButton;
use app\modules\prom\components\ForcedReportButton;
use app\modules\prom\components\InterventionWidget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use Yii;

class TrafficController extends Controller
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
                                'index',

                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['PromAdmin'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex() {

        $modems=CorrectorToCounter::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$modems,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);


        echo $this->render('index',['dataProvider'=>$dataProvider]);
    }



}