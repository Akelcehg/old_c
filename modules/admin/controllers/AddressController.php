<?php

namespace app\modules\admin\controllers;

use app\components\Error;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use app\models\Address;
use app\components\Events;

class AddressController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
   // public $layout = 'smartAdmin';

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
                                'addaddress',
                                'editaddress',
                            ],
                            'allow' => true,
                            //Admin and region admin can  make actions to addresses
                            'roles' => ['admin', 'regionWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex()
    {
        $address = Address::find();

        if (User::is('regionWatcher') && !User::is('admin')) {
            $currentUser = new User();
            $address->where(['region_id' => $currentUser->getCurrentUserRegionId(Yii::$app->user->id)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $address,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'address' => $address,
        ]);
    }

    public function actionAddaddress()
    {
        $address = new Address();

        if ( $addressData = Yii::$app->request->post('Address', False)) {

            $address->setAttributes($addressData, false);

            if (User::is('regionWatcher')) {
                $currentUser = new User();
                $addressData['region_id'] = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
            }


            if ($address->save()) {

                Yii::$app->session->setFlash('save', 'Сохранено!');
                $this->refresh();

            }
        }

        echo $this->render('addaddress', [
            'address' => $address,
        ]);
    }

    public function actionEditaddress()
    {
        $id = Yii::$app->request->get('id');

        $address = Address::find()->where(['id' => $id])->one();

        if(!$address)
        {

        }

        $currentUser = new User();

        if ($addressData = Yii::$app->request->post('Address', False)) {


            if (User::is('regionWatcher')) {
                $addressData['region_id'] = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
            }

            $address->setAttributes($addressData, false);
            $events = new Events();
            $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            $events->oldAttributes = $address->getOldAttributes();

            if ($address->save()) {
                $events->newAttributes = $address->getAttributes();
                $events->model = $address;
                $events->type = 'edit';
                $events->AddEvent();

                Yii::$app->session->setFlash('save', 'Сохранено!');

            }
        }

        echo $this->render('editaddress', [
            'address' => $address,
        ]);
    }

}
