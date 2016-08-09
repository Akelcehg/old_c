<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AlertInput
 *
 * @author alks
 */

namespace app\modules\admin\controllers;

use app\models\AlertsListSearch;
use TelegramBot\Api\Types\User;
use yii\web\Controller;
use Yii;
use app\components\Alerts;
use app\models\AlertsList;
use yii\filters\AccessControl;
use app\models\AlertsToUser;
use app\models\AlertsTypes;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\components\Events\Events;
use \app\models\UserCounters;
use \app\models\CounterAddress;
use yii\web\HttpException;
use app\components\RightsComponent;

//http://counter.test/admin/alertinput/
class AlertController extends Controller {

    //put your code here

    public $layout = 'smartAdmin';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                [
                    [
                        'actions' => [
                            'index',
                            'counters'
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                    ,
                    [
                        'actions' => [
                            'index',
                            'counters',
                            'editalerts',
                            'editalertstypetouser',
                            'viewalertstype',
                            'viewalertshistory',
                            'viewalertshistorybyuserconfig'
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

        $type = Yii::$app->request->get('type', null);
        $device_type = Yii::$app->request->get('device_type', null);
        $serialNumber = Yii::$app->request->get('serialNumber', null);
       
        if(Alerts::AddInAlertsList($serialNumber, $type, $device_type))
        {
            echo 'принято';
        }
        {
            echo 'no!';
        }
    }
    
     public function actionCounters() {

        
        $serialNumber = Yii::$app->request->get('serialNumber', null);
        
        $counter= UserCounters::findOne(['serial_number'=>$serialNumber]);
        $address= CounterAddress::findOne(['id'=>$counter->geo_location_id]);
        if($address->exploitation != 1){
        Alerts::AddInAlertsList($serialNumber, 'leak');}
    }

    public function actionViewalertshistory() {


        $searchModel = new AlertsListSearch();

        $searchModel->status = Yii::$app->request->get('status', null);
        $searchModel->serial_number = Yii::$app->request->get('serial_number', null);
        $searchModel->type = Yii::$app->request->get('type',null);
        $searchModel->device_type = Yii::$app->request->get('device_type', null);
        $searchModel->created_at = Yii::$app->request->get('created_at', null);
        $searchModel->user_modem_id = Yii::$app->request->get('user_modem_id', null);

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        echo $this->render('alertsHistory', [
            'dataProvider' => $dataProvider,
            'searchModel' =>$searchModel
        ]);
    }

    public function actionViewalertshistorybyuserconfig() {
        $type = AlertsToUser::find()->where(['user_id' => Yii::$app->user->id])->all();

        $alerts = AlertsList::find()->where(['in', 'type', ArrayHelper::map($type, 'alerts_type_id', 'alerts_type_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $alerts,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        echo $this->render('alertsHistory', [
            'dataProvider' => $dataProvider,
            'types' => $alerts
        ]);
    }

    public function actionEditalerts() {
        $id = Yii::$app->request->get('id');
        $alertData = Yii::$app->request->post('AlertsList', false);
        $alert = AlertsList::find()->where(['id' => $id])->one();

        if(!$alert){
            throw new HttpException(404, 'Предупреждение не найдено');
        };

        if ($alertData) {

            $alert->setAttributes($alertData, false);
            
            $events = new Events();
            $events->oldAttributes = $alert->getOldAttributes();
            
            if ($alert->save()) {

                $currentUser = new \app\models\User();
                $events->newAttributes = $alert->getAttributes();
                $events->model = $alert;
                //$events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
                $events->type = 'edit';
                //$events->description = 'Редактирование Предупреждения №' . $id;
                $events->AddEvent();
                
                //Events::AddEvent('alert','Редактирование Предупреждения №'.$id,$alert);
                return $this->redirect('viewalertshistory');
            }
        }


        echo $this->render('editAlerts', [
            'alert' => $alert
        ]);
    }

    public function actionEditalertstypetouser() {
        $alertsTypes = Yii::$app->request->post('alertsType');

        if (Yii::$app->request->isPost) {
            AlertsToUser::deleteAll(['user_id' => Yii::$app->user->id]);
            if ($alertsTypes) {
                foreach ($alertsTypes as $type) {
                    $alertsType = new AlertsToUser();
                    $alertsType->user_id = Yii::$app->user->id;
                    $alertsType->alerts_type_id = $type;
                    $alertsType->save();
                }
            }
        }

        $type = AlertsToUser::find()->where(['user_id' => Yii::$app->user->id])->all();

        echo $this->render('editAlertsTypeToUser', [
            'type' => $type
        ]);
    }

    public function actionViewalertstype() {
        $types = AlertsTypes::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $types,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        echo $this->render('alertsTypes', [
            'dataProvider' => $dataProvider,
            'types' => $types
        ]);
    }

}
