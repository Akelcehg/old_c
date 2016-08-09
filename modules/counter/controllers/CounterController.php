<?php

namespace app\modules\counter\controllers;

use app\components\ChartCalc;
use app\models\Modem;
use app\models\ModemDCommandConveyor;
use app\models\SimCard;
use app\modules\admin\components\Counter;
use app\modules\counter\components\ForcedPaymentButton;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CounterController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'smartAdminN';

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
                                'view',
                                'ajaxconsumpmonth',
                                'ajaxcheckforcedpayment'
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'waterWatcher', 'regionWatcher'],
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

        public function actionView()
    {
        $id = Yii::$app->request->get('id', FALSE);

        $this->layout='smartAdminN';

        $address = new Counter();
        if($address->EditCounter())
        {
            $this->refresh();
        }

        $modem=Yii::$app->request->post('Modem', FALSE);
        if($modem){
            $modem = new \app\modules\admin\components\Modem();
            $modem->EditModem(false);
        }

        $simCardPost=Yii::$app->request->post('SimCard', FALSE);
        if($simCardPost){
            $simCard = SimCard::find()->where(['modem_id'=>$simCardPost['modem_id']])->one();
            $simCard->setAttributes($simCardPost,false);
            $simCard->save();

        }




        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $id;
        $chartCalc->beginDate =date("Y-m-01");
        $chartCalc->endDate =date("Y-m-t");


        $label = $chartCalc->graph();
        $monthTempArray = $chartCalc->tempChart();

        $labels=[];
        $labelsTemp=[];

        for($i=0;$i<count($label);$i++){

            $labelArr=explode('-',$label[$i]["label"]);

            $labels["labels"][]=$labelArr[0];
            $labels["data"][]=$label[$i]["data"][0];
        }

        for($i=0;$i<count($monthTempArray)-1;$i++){
            $labelArr=explode('-',$monthTempArray[$i]["label"]);
            $labelsTemp["labels"][]=$labelArr[2];
            $labelsTemp["data"][]=$monthTempArray[$i]["data"][0];
        }

        $oDU=$monthTempArray[$i];

        $userRoles = array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->id));

        return $this->render('view',[

            'counter' => $address->getModel(),
            'userRoles'=>$userRoles,
            'labels'=>$labels,
            'labelsTemp'=>$labelsTemp,
            'oDU'=>$oDU,
        ]);
    }



  public function actionAjaxconsumpmonth($id)
    {
        $this->layout='onlyGrid';
        $counter=\app\models\Counter::findOne($id);

        return $this->render('_indication',['counter'=>$counter]);

    }




}
