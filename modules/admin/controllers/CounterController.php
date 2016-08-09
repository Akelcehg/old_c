<?php

namespace app\modules\admin\controllers;

//use app\components\Counter;
use app\models\AlertsList;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use Yii;
use app\models\User;

use yii\filters\AccessControl;
use app\models\UserIndications;
use app\models\Regions;
use app\models\CounterAddress;
use app\components\CounterQuery;
use app\components\Alerts;
use app\models\CounterAddressSearch;
use app\models\UserModems;
use app\models\UserCountersSearch;
use app\models\IndicationSearch;
use app\components\Events;
use app\models\CounterModel;
use yii\web\HttpException;
use app\components\RightsComponent;

use app\models\Address;
use app\models\AddressSearch;
use app\models\Counter;
use app\models\CounterSearch;
use app\models\Modem;





class CounterController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout = 'smartAdmin';

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
                                'alerts',
                                'tabs',
                                'maps',
                                'graph',
                                'deletecountermodel',
                                'viewcountermodel',
                                'getindications',
                                'addcountermodel',
                                'allcounterlist',
                                'allindicationlist',
                                'ajaxlistcounter',
                                'ajaxlistcounterbyfile',
                                'ajaxlistcounterbyfile1c',
                                'ajaxlistcounterbyexcel',
                                'ajaxlistcity',
                                'ajaxlisthouse',
                                'ajaxcountertochart',
                                'ajaxcountertochartbyday',
                                'ajaxcountertemptochartbyday',
                                'ajaxcountertochartbyweek',
                                'ajaxcountertoconsumtiondetailbyweek',
                                'ajaxcountertomap',
                                'ajaxlistcounteralerts',

                                'ajaxcountertocalendar',
                                'nonmodemcounterslist',
                                'ajaxgetcounterdata',
                                'ajaxcountersave',
                                'ajaxuploadimage',
                                'ajaxcountertoconsumtiondetail',
                                'houseindicationsforperiod',
                                'modemslist',
                                'export',
                                'exportexcel',
                                'exportimage',
                                'editmodem',
                                'editcounter',
                                'editcountermodel',
                                'counterevents',
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'waterWatcher', 'regionWatcher'],
                        ]
                        ,
                        [
                            'actions' => [
                                'addcounter',
                                //'deletecounter',
                                'addindications',
                                'addaddress',
                                'testdata',
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

    public function actionIndex()
    {

        $address = new \app\modules\admin\components\Counter();
        $address->CounterAddressList();

        if (Yii::$app->request->isAjax) {

            return $this->renderAjax('indexGridOnly', [
                'dataProvider' => $address ->getDataProvider(),
                'address' => $address ->getModel(),
                'searchModel' => $address ->getSearchModel(),
            ]);
        }

        return $this->render('index', [
            'dataProvider' =>  $address->getDataProvider(),
            'address' => $address->getModel(),
            'searchModel' => $address->getSearchModel(),
        ]);

    }

    public function actionTabs()
    {

        $address = new \app\modules\admin\components\Counter();
        $address->CounterAddressList();

        if (Yii::$app->request->isAjax) {

            return $this->renderAjax('indexGridOnly', [
                'dataProvider' => $address->getDataProvider(),
                'address' => $address->getModel(),
                'searchModel' => $address->getSearchModel(),
            ]);
        }


        return $this->render('tabs', [
            'dataProvider' => $address->getDataProvider(),
            'address' => $address->getModel(),
            'searchModel' => $address->getSearchModel(),
        ]);
    }

    public function actionAlerts()
    {

        return $this->render('alerts');

    }

    public function actionMaps()
    {

        return $this->render('maps');

    }


    public function actionAjaxlistcounteralerts()
    {

        $counterList =Counter::find()
            ->orderBy('flat')
            ->joinWith('user')
            ->joinWith('address');


        $dataProvider = new ActiveDataProvider([
            'query' => $counterList,
            'sort' => [
                'attributes' => ['flat'],
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $array=AlertsList::find()
            ->where(['type'=> Yii::$app->request->get('alerts_type', null)])
            ->andWhere(['status'=>'ACTIVE'])
            ->all();
        $snArray=['empty'];

        foreach($array as $onecounter){

            $snArray[]=$onecounter->serial_number;

        }



        $searchModel = new CounterSearch();
        $searchModel->distinct = true;
        $searchModel->serial =$snArray;
        $searchModel->beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
        $searchModel->endDate = Yii::$app->request->get('endDate', date('Y-M-d'));

        //$searchModel->leak = Yii::$app->request->get('leak', 0);
       // $searchModel->tamper = Yii::$app->request->get('tamper', 0);
        //$searchModel->magnet = Yii::$app->request->get('magnet', 0);
        //$searchModel->disconnect = Yii::$app->request->get('disconnect', 0);
        //$searchModel->lowBatteryLevel = Yii::$app->request->get('lowBatteryLevel', 0);

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $this->layout = 'onlyGrid';
        $gridViewId = 'browse-counterAlerts-grid';
        echo $this->render('_counterListAjax', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'counterList' => $counterList,
                'gridViewId' => $gridViewId
            ]
        );
    }

    public function actionAllindicationlist()
    {

        $searchModel = new IndicationSearch();

        $searchModel->counter_id = Yii::$app->request->get('counter_id');
        $searchModel->indications = Yii::$app->request->get('indications');
        $searchModel->created_at = Yii::$app->request->get('created_at');

        //$rights = new  RightsComponent();
        //$searchModel = $rights->updateSearchModelCounter($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        echo $this->render('allindicationList', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'gridViewId' => 'all-indications-grid'
            ]
        );
    }

    public function actionGetindications()
    {

        $searchModel = new IndicationSearch();

        $searchModel->counter_id = Yii::$app->request->get('counter_id');
        $searchModel->indications = Yii::$app->request->get('indications');
        $searchModel->created_at = Yii::$app->request->get('created_at');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        echo $this->render('allindicationList', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'gridViewId' => 'all-indications-grid'
            ]
        );
    }

    public function actionAllcounterlist()
    {
        $counters=new \app\modules\admin\components\Counter();
        $counters->paginationSize=15;
        $counters->CounterList();

        if (Yii::$app->request->isAjax) {
            $this->layout = 'onlyGrid';
            echo $this->render('_allcounterListGridOnly', [
                    'dataProvider' => $counters->getDataProvider(),
                    'searchModel' => $counters->getSearchModel(),
                    'counterList' => $counters->getModel(),
                    'gridViewId' => 'all-counter-grid'
                ]
            );
        } else {


            echo $this->render('allcounterList', [
                    'dataProvider' => $counters->getDataProvider(),
                    'searchModel' => $counters->getSearchModel(),
                    'counterList' => $counters->getModel(),
                    'gridViewId' => 'all-counter-grid'
                ]
            );
        }
    }

    public function actionAjaxlistcounter()
    {
        $counters = new \app\modules\admin\components\Counter();
        $counters ->paginationSize =20;
        $counters->CounterList();

        $this->layout = 'onlyGrid';
        $gridViewId = 'browse-flatCounter-grid';

        echo $this->render('_counterListAjax', [
                'dataProvider' => $counters->getDataProvider(),
                'searchModel' => $counters->getSearchModel(),
                'counterList' => $counters->getModel(),
                'gridViewId' => $gridViewId . Yii::$app->request->get('geo_location_id')
            ]
        );
    }



    public function actionAjaxcountertomap()
    {
        $address= new \app\modules\admin\components\Counter();
        $address->paginationSize=false;
        $address->CounterAddressList();
        $countersList = $address->getDataProvider()->getModels();

        Yii::$app->response->format = 'json';
        return $countersList;
    }





    public function actionAjaxlistcity()
    {
        $geoId = Yii::$app->request->get('geo_location_id', 0);

        $cityList = Regions::find()->where('parent_id=:parent_id', [':parent_id' => $geoId])->all();

        $output = '<option value="0">Выберите город</option>';

        foreach ($cityList as $oneCity) {
            $output .= '<option value="' . $oneCity->id . '">' . $oneCity->name . '</option>';
        }

        echo $output;
    }

    public function actionNonmodemcounterslist()
    {
        $counterList = Counter::find()->where('serial_number IS NULL')
            ->orderBy('flat')
            ->joinWith('user')
            ->joinWith('address');

        $searchModel = new CounterSearch();
        $searchModel->nonDefinedCounter = true;

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        echo $this->render('nonModemCountersList', [
                'dataProvider' => $dataProvider,
                'counterList' => $counterList,
                'searchModel' => $searchModel,
            ]
        );
    }

    public function actionHouseindicationsforperiod()
    {


        $id = Yii::$app->request->get('geo_location_id', 0);

        $flatCounters = Counter::find()->where('geo_location_id =:geo_location_id', [':geo_location_id' => $id])
            ->andWhere('counters.serial_number IS NOT NULL');


        if (array_key_exists('user', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' => Yii::$app->user->id]);
        }

        $flatCountersAll = $flatCounters->all();

        $indications = 0;


        foreach ($flatCountersAll as $counter) {
            $indications += $counter->flatindications;
        }

        return round($indications, 3);
    }



    public function actionEditcounter()
    {
        $counters=new \app\modules\admin\components\Counter();


        if($counters->EditCounter()){

            Yii::$app->session->setFlash('save', 'Сохранено!');
            echo $this->refresh();

        }else{

            if (Yii::$app->request->isPost) {
                Yii::$app->session->setFlash('save', '');
            }

        }

        $userRoles = array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->id));

        echo $this->render('editcounter', [
            'counter' => $counters->getModel(),
            'userRoles' => $userRoles,
        ]);
    }

}
