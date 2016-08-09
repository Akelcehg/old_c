<?php

namespace app\modules\mount\controllers;

use app\components\Counter;

use app\models\Indication;
use app\modules\api\v1\models\UserCounter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\Events;

class StepController extends Controller
{
    public $modelClass = 'app\modules\mount\models\UserCounter';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [

                        [
                            'actions' => [
                                'step1',
                                'step2',
                                'step3',
                                'indicationshistory',

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


    public function actionStep1()
    {
        if ($serial_number = Yii::$app->request->get('serial_number', FALSE)) {

            $rmodule = \app\models\Rmodule::find();
            $rmodule->andFilterWhere(['serial_number' => $serial_number]);

            $rmodule = $rmodule->one();

            $counter = $rmodule->counter;

            $path = rtrim(Yii::$app->params['countersPhotoPath'], DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR . $rmodule->counter_id . DIRECTORY_SEPARATOR;

            if (Yii::$app->request->isPost) {

                $rmoduleData = Yii::$app->request->post();

                $hasImage = false;

                $image = Yii::$app->request->post('CounterImage', False);

                if ($image) {

                    $hasImage = true;
                }

                $counterCheck = \app\models\Counter::find()->where(['serial_number' => $rmoduleData['Counter']['serial_number'], 'type' => $rmoduleData['Counter']['type']])->one();

                if ($counterCheck) {
                    $counter = $counterCheck;
                } else {

                    $counter->setAttributes(array_merge($rmoduleData['Rmodule'], $rmoduleData['Counter'],[
                        'rmodule_id' => $rmodule->serial_number,
                        'modem_id'=>$rmodule->modem_id]), false);

                    if ($counter->save()) {
                        $events = new Events();

                        $events->newAttributes = $counter->getAttributes();
                        $events->model = $counter;

                        $events->type = 'add';

                        $events->AddEvent();

                        $counter =\app\models\Counter::findOne(['id'=>$counter->id]);
                    }else
                    {

                        return $this->render('step1', [
                            'rmodule' => $rmodule,
                            'counter' => $counter,
                            'images' => glob($path . '/*.*')
                        ]);

                    }

                }
                $arr=array_merge(
                    $rmoduleData['Rmodule'],
                    $rmoduleData['Counter'],
                    [
                        'rmodule_id' => $rmodule->serial_number,
                        'modem_id'=>$rmodule->modem_id,
                        'counter_id'=>$counter->id
                    ]
                );


                $counterComponent = new Counter($counter,$arr, []);


                if ($counterComponent->saveWithImage($hasImage, $image)) {

                    $rmodule->setAttributes( $rmoduleData['Rmodule']+['counter_id'=>$counter->id], false);
                    if ($rmodule->save()) {
                        return $this->redirect('step2?counter_id=' . $counter['id']);
                    }else
                    {

                        return $this->render('step1', [
                            'rmodule' => $rmodule,
                            'counter' => $counter,
                            'images' => glob($path . '/*.*')
                        ]);
                    }

                }
                else{



                    return $this->render('step1', [
                        'rmodule' => $rmodule,
                        'counter' => $counterComponent->userCounter,
                        'images' => glob($path . '/*.*')
                    ]);

                }

            } else {



                return $this->render('step1', [
                    'rmodule' => $rmodule,
                    'counter' => $counter,
                    'images' => glob($path . '/*.*')
                ]);
            }

        }
    }

    public function actionStep2()
    {
        if ($counter_id = Yii::$app->request->get('counter_id', FALSE)) {

            $counter = \app\models\Counter::find();
            $counter->andFilterWhere(['id' => $counter_id]);

            $counter = $counter->one();

            if (Yii::$app->request->isPost) {

                $counterData = Yii::$app->request->getBodyParams();

                $counterComponent = new Counter($counter, $counterData['Counter'], []);

                if ($counterComponent->saveIndications()) {

                    return $this->redirect('step3?counter_id=' . $counter['id']);

                }

            } else {
                return $this->render('step2', [
                    'counter' => $counter
                ]);
            }
        }
    }

    public function actionStep3()
    {

        if ($counter_id = Yii::$app->request->get('counter_id', FALSE)) {
            $counter = \app\models\Counter::find();
            $counter->andFilterWhere(['id' => $counter_id]);
            $counter = $counter->one();
            $path = rtrim(Yii::$app->params['countersPhotoPath'], DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR . $counter['id'] . DIRECTORY_SEPARATOR;
            return $this->render('step3', [
                'counter' => $counter,
                'images' => glob($path . '/*.*')
            ]);
        }
    }

    public function actionIndicationshistory()
    {

        if ($counter_id = Yii::$app->request->get('counter_id', FALSE)) {


            $indications=Indication::find()->where(['counter_id'=>$counter_id])->limit(10)->orderBy(['created_at'=>SORT_DESC])->all();
        }else
        {
            $indications=false;
        }


        return $this->render('indicationsHistory', [
            'indications' => $indications,

        ]);
    }

}
