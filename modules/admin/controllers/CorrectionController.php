<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorrectionController
 *
 * @author alks
 */
namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\models\Correction;
use app\models\Indication;
use Yii;
use app\components\Alerts;
use app\models\AlertsList;
use yii\filters\AccessControl;
use app\models\AlertsToUser;
use app\models\AlertsTypes;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\components\Events;
use yii\web\User;

class CorrectionController extends Controller
{

  //  public $layout = 'smartAdmin';

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
                                'addcorrection'

                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'regionWatcher', 'waterWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex()
    {
        $corrections = Correction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $corrections,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        echo $this->render('index', [
            'dataProvider' => $dataProvider,
            'corrections' => $corrections
        ]);
    }



    public function actionAddcorrection()
    {

        $indicationSearchData = Yii::$app->request->get('counter_id', False);

        $oldIndication = Indication::find()
            ->where(['counter_id' => $indicationSearchData])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();

        //previous indication not exists - we need make redirect
        if (!$oldIndication) {
            Yii::$app->getSession()->setFlash('previous_indication_not_exists', "Нет показаний для коррекции");
            return $this->redirect(Yii::$app->urlManager->createUrl(['/admin/counter/getindications', 'counter_id' => $indicationSearchData]));
        }

        $indication = new Indication();
        $indication->counter_id = $indicationSearchData;
        $indicationData = Yii::$app->request->post('Indication', False);
        $description = Yii::$app->request->post('description', False);

        if ($indicationData) {
            $indication->setAttributes($indicationData, false);
            //$indication->impuls = $oldIndication->impuls;
            $indication->created_at = date('Y-m-d H:i:s');

            if ($indication->save()) {
                $correction = new Correction();
                $correction->counter_id = $indicationSearchData;
                $correction->new_indications_id = $indication->id;
                $correction->old_indications_id = $oldIndication->id;
                $correction->description = $description;

                if ($correction->save()) {

                    $userCounter = $correction->getUserCounter()->one();
                    if ($userCounter) {
                        $userCounter->last_indications = $indication->indications;
                        $userCounter->update(false, ['last_indications']);
                    }


                    $currentUser = new \app\models\User();
                    $events = new Events();
                    $events->type = 'add';
                    $events->newAttributes = $correction->getAttributes();
                    $events->model = $correction;
                    $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
                    $events->counter_type = $userCounter->type;

                   // $events->description = 'Внесение поправки для счетчика № ' . $userIndicationSearchData['user_counter_id'];
                    $events->AddEvent();

                    //Events::AddEvent('correction','Внесение поправки для счетчика № '.$userIndicationSearchData['user_counter_id'],$correction);
                    return $this->redirect(Yii::$app->urlManager->createUrl(['/admin/counter/getindications', 'counter_id' => $indicationSearchData]));
                } else {
                    $indication->delete();
                }
            }
        }

        echo $this->render('addCorrection', [
            'oldIndication' => $oldIndication,
            'indication' => $indication
        ]);
    }


}
