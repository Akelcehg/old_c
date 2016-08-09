<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 11:49
 */

namespace app\modules\admin\controllers;


use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use app\models\User;
use app\models\Modem;
use app\components\CounterQuery;
use yii\data\ActiveDataProvider;


class ModemController extends Controller
{

   // public $layout = 'smartAdmin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => [
                                'modemslist',
                                'editmodem',
                                'ajaxlistcounteralertsmodems',
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'waterWatcher', 'regionWatcher'],
                        ]
                        ,
                        [
                            'actions' => [

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

    public function actionModemslist()
    {

        $modemList= new \app\modules\admin\components\Modem();

        $modemList->ModemList();

        return $this->render('modems', [
                'dataProvider' => $modemList->getDataProvider(),
                'searchModel'=>$modemList->getSearchModel()
            ]
        );
    }

    public function actionEditmodem()
    {
        $modem = new \app\modules\admin\components\Modem();
        $modem->EditModem();

        echo $this->render('editmodem', [
            'modem' => $modem->getModel(),
            'counters' => $modem->getDataProvider()
        ]);
    }

    public function actionEditsimcard()
    {
        $modem = new \app\modules\admin\components\Modem();
        $modem->EditSimCard();

        echo $this->render('editSim', [
            'modem' => $modem->getModel(),
        ]);
    }





    public function actionAjaxlistcounteralertsmodems()
    {
       /* $data = Yii::$app->request->get('data', 0);
        $geoId = Yii::$app->request->get('city', null);

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $whereInstalled = Yii::$app->request->get('where_installed', 0);

        if (count($data) == 1) {
            $string = null;
        } else {
            $string = implode(',', $data);
        }

        $counterList = Modem::find()
            ->FilterWhere(['in', 'modems.serial_number', $data])
            ->joinWith('address')
            ->joinWith('counters');

        if ($whereInstalled) {
            $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
        }


        if (CounterQuery::isRole('admin')) {
            //->joinWith('indications')->joinWith('lastFlatIndications')->joinWith('firstFlatIndications')->distinct();
            //$counterList->andWhere('user_counters.geo_location_id =:geo_location_id', [':geo_location_id' => $geoId]);
        }

        if (CounterQuery::isRole('user')) {
            $counterList->andFilterWhere(['user_counters.geo_location_id' => $geoId]);
            $counterList->andWhere('user_counters.user_id =:user_id', [':user_id' => Yii::$app->user->id]);
        }


        if (CounterQuery::isRole('gasWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counterList->andWhere('counter_address.region_id =:region_id', [':region_id' => $user->geo_location_id]);
            //$counterList->andWhere('user_counters.geo_location_id =:geo_location_id', [':geo_location_id' => $geoId]);
        }

        $counterList->andWhere('user_counters.real_serial_number IS NOT NULL');


        $dataProvider = new ActiveDataProvider([
            'query' => $counterList,
            'sort' => [
                'attributes' => ['flat'],
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
            */

        $modemList= new \app\modules\admin\components\Modem();
        $modemList->ModemList();

        $this->layout = 'onlyGrid';
        $gridViewId = 'browse-counterAlerts-grid';
        echo $this->render('_modemsListAlerts', [
                'dataProvider' => $modemList->getDataProvider(),
                'counterList' => $modemList->getModel(),
                'gridViewId' => $gridViewId
            ]
        );
    }


}