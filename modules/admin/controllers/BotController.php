<?php

namespace app\modules\admin\controllers;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BotController
 *
 * @author alks
 */
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\Testbot;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\TelegramToUser;

class BotController extends Controller {

    //put your code here

    //public $layout = 'smartAdmin';

    public function beforeAction($action) {


        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                [
                    [
                        'actions' => [
                            'input',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                    ,
                    [
                        'actions' => [
                            'index',
                            'setwebhooks'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ,
                ]
            ,
            ],
        ];
    }

    public function actionIndex() {

        $testBot = Testbot::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $testBot,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);


        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'testBot' => $testBot,
        ]);
    }

    public function actionSetwebhooks() {

        $url=Yii::$app->urlManager->createAbsoluteUrl(['/admin/bot/input']);

        $result=Yii::$app->bot->setWebhook($url, Yii::$app->params['sslkey']);
        echo $url.' - '.$result;

    }

    public function actionInput() {

        Yii::$app->request->post();
        $input = Yii::$app->getRequest()->getBodyParams();

        // $testBot=new Testbot();

        $input['message']['from']['id'] . '--------' . $input['message']['text'];

        $textArray = explode(' ', $input['message']['text']);


        switch ($textArray[0]) {
            case "/registerAlerts":
                $this->registerAlerts($textArray,$input['message']['from']['id']);
                break;
            case "/unregisterAlerts":
                $this->unRegisterAlerts($textArray,$input['message']['from']['id']);
                break;

            default:
                Yii::$app->bot->sendMessage($input['message']['from']['id'], 'Для  подписки на рассылку предупреждений введите " /registerAlerts ваш@email " емейл  должен совпадать с  указанными в профиле на сайте ');
                break;
        }
    }

    public function registerAlerts($text,$fromId) {

        $user = User::find()->where(['email' => $text[1]])->one();
        if (isset($user)) {
            
            $telToUser= new TelegramToUser();
            $telToUser->user_id=$user->id;
            $telToUser->telegram_id=''.$fromId;
            if($telToUser->save())
                {
                    Yii::$app->bot->sendMessage($fromId, 'Вы успешно подписались на  получение предупреждений , настройка  предупреждений по типам  доступна на сайте ');
                    return true;
                }
                else
                {
                   Yii::$app->bot->sendMessage($fromId, 'error - '.$user->id.'-'.$fromId.' not save');
                   return  false;
                }
            
        } else {
            
            Yii::$app->bot->sendMessage($fromId,'Email не найден. Подписка на получение предупреждений доступна только  для  пользователей сайта . Проверьте написание Email либо зарегестрируйтесь');
            return false;
            
        }
    }
    
      public function unRegisterAlerts($text,$fromId) {

        $user = User::find()->where(['email' => $text[1]])->one();
        if (isset($user)) {
            if(TelegramToUser::deleteAll(['user_id'=>$user->id]))
                {
                    Yii::$app->bot->sendMessage($fromId, 'Вы успешно отписались  от  получения предупреждений ');
                    return true;
                }
                else
                {
                   Yii::$app->bot->sendMessage($fromId, 'error - '.$user->id.'-'.$fromId.' not save');
                   return  false;
                }
            
        } else {
            
            Yii::$app->bot->sendMessage($fromId,'Email не найден. Подписка на получение предупреждений доступна только  для  пользователей сайта . Проверьте написание Email либо зарегестрируйтесь');
            return false;
            
        }
    }

}
