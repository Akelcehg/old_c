<?php

namespace app\modules\mount\controllers;

use app\components\Counter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
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
                            'roles' => ['admin', 'adjuster'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex()
    {
        $rmodule = \app\models\Rmodule::find();

        $serial_number = Yii::$app->request->get('serial_number');
        $modem_id = Yii::$app->request->get('modem_id');
        $counter_id = Yii::$app->request->get('counter_id');

        if ($serial_number != '') {
            $rmodule->andWhere(["serial_number" => $serial_number]);
        }

        if ($modem_id != '') {
            $rmodule->andWhere(["modem_id" => $modem_id]);
        }

        if ($counter_id != '') {
            $rmodule->andWhere(["counter_id" => $counter_id]);
        }

        if ($serial_number != '' || $modem_id != '' || $counter_id != '') {
            return $this->render('index', [
                'rmodule' => $rmodule->all(),
                'message' => 'Радиомодуль не найден'
            ]);
        } else
            return $this->render('index', [
                'counter' => []
            ]);

    }


}
