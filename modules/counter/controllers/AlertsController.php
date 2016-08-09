<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.03.16
 * Time: 11:28
 */

namespace app\modules\counter\controllers;

use app\components\Alerts\AlertsHandler;
use app\components\Alerts\widgets\AlertsTabWidget;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AlertsController extends Controller
{
    public $layout = 'smartAdminN';


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
                                'editalerts',
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'waterWatcher', 'regionWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex()
    {


 return $this->render('index');
    }

    public function actionEditalerts()
    {
        $alert=new AlertsHandler();


        echo $this->render('editAlerts', [
            'alert' => $alert->EditAlerts()
        ]);
    }

}