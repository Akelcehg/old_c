<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 14:21
 */

namespace app\modules\admin\controllers;


use app\modules\admin\components\CounterEvents;
use yii\web\Controller;
use Yii;


class CountereventsController extends Controller
{
   // public $layout = 'smartAdmin';

    public function actionIndex(){

        $counterEvents=new CounterEvents();
        $counterEvents->CounterEvents();

        echo $this->render('counterEvents', [
                'dataProvider' => $counterEvents->getDataProvider(),
                'searchModel' => $counterEvents->getSearchModel(),
            ]
        );

    }

    public function actionAjaxcountertocalendar()
    {
        $counterEvents=new CounterEvents();
        Yii::$app->response->format = 'json';
        return $counterEvents->CounterToCalendar();
    }

    public function actionAjaxgetcounterdata()
    {
        $counterEvents=new CounterEvents();
        Yii::$app->response->format = 'json';
        return $counterEvents->GetCounterData();
    }

    public function actionAjaxcountersave()
    {

        $counterEvents=new CounterEvents();
        return $counterEvents->CounterSave();

    }

}