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

use app\components\RightsComponent;
use app\models\User;
use app\models\UserTrackingSearch;
use yii\web\Controller;
use app\models\Correction;
use app\models\UserIndications;
use Yii;
use app\components\Alerts;
use app\models\AlertsList;
use yii\filters\AccessControl;
use app\models\AlertsToUser;
use app\models\AlertsTypes;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\EventLogSearch;

class EventController extends Controller
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
                                ,'tracking'

                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher','regionWatcher', 'waterWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }
    
  public function actionIndex() {
      
    $searchModel = new EventLogSearch();
    $searchModel->user_id =null; //Yii::$app->user->id;
    $searchModel->type = Yii::$app->request->post('type', null);
    $searchModel->description = Yii::$app->request->post('description', null);
    $searchModel->created_at = Yii::$app->request->post('created_at', null);
    $searchModel->pagination=['pageSize' => 10,];

       $rights = new  RightsComponent();
      $searchModel = $rights->updateSearchModelCounter($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        echo $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionTracking() {

        $searchModel = new UserTrackingSearch();
        $searchModel->user_id =Yii::$app->request->get('id', null);; //Yii::$app->user->id;

        $searchModel->pagination=['pageSize' => 10,];



        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        echo $this->render('tracking', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


}
