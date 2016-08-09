<?php

namespace app\modules\metrix\controllers;

use app\components\ChartCalc;
use app\models\ModemStatus;
use app\models\SimCard;
use app\modules\metrix\components\MetrixCommandGenerator;
use app\modules\metrix\models\MetrixCounter;
use app\modules\metrix\models\MetrixIndication;
use app\modules\metrix\models\MetrixModemStatus;
use app\modules\metrix\models\MetrixSimCard;
use app\modules\prom\components\Limit\Limits;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use Yii;

class CountersController extends Controller
{
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
                                'open'
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['metrix'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex() {

        $query = MetrixCounter::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        $globalChartSettings = [
            'responsive' =>'false',
            'animation' => 'false',
            'showTooltips'=>'false',
            'tooltipTemplate' => '"<%= value %> куб."'];


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'globalChartSettings'=>$globalChartSettings,

        ]);
    }

    public function actionView($id) {

        $counter = MetrixCounter::findOne($id);
        $counterPost=Yii::$app->request->post('MetrixCounter', FALSE);

        if($counterPost){




            $counter->setAttributes($counterPost,false);
            if($counter->save())
            {$this->refresh();}

        }

        if (!$counter) {
            throw new HttpException(404, 'Cчетчик не найден');
        }

        $simCardPost=Yii::$app->request->post('MetrixSimCard', FALSE);

        if($simCardPost){
            $simCard = MetrixSimCard::find()->where(['modem_id'=>$simCardPost['modem_id']])->one();
            $simCard->setAttributes($simCardPost,false);
            $simCard->save();

        }

        $сorrectorToCounterPost=Yii::$app->request->post('CorrectorToCounter', FALSE);

        if($сorrectorToCounterPost){

            $model = $counter->corrector;
            $model->setAttributes($сorrectorToCounterPost,false);

            if($model->validate() and ($counter->corrector->getOldAttribute('update_interval')!=$сorrectorToCounterPost['update_interval']) ){

                $command=new MetrixCommandGenerator();
                $command->counter_id=$counter->id;
                $command->changeTimeInterval($сorrectorToCounterPost['update_interval']);

            }

            $model->save();

        }

        $limitPost=Yii::$app->request->post('limit', false);


        if(Yii::$app->request->isPost) {

            $limit = new Limits();

            $limit->all_id = $counter->corrector->id;
            $limit->year = date('Y');
            $limit->month = date('m');

            if ($limit->GetLimit()) {
                $limit->id = $limit->GetLimit()->id;
                $limit->EditLimit($limitPost);


            } else {

                $limit->CreateLimit($limitPost);


            }
        }



        $limitNMPost=Yii::$app->request->post('limitNM', FALSE);

        if(Yii::$app->request->isPost) {
            $dt=new \DateTime();
            $dt->add(new \DateInterval("P1M"));
            $limit = new Limits();

            $limit->all_id= $counter->corrector->id;
            $limit->year=$dt->format('Y');
            $limit->month=$dt->format('m');

            if ($limit->GetNextMonthLimit()) {
                $limit->id=$limit->GetNextMonthLimit()->id;
                $limit->EditLimit($limitNMPost);

            } else {

                $limit->CreateLimit($limitNMPost);

            }

        }


        $userRoles = array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->id));

        $chartCalc = new ChartCalc();
        $chartCalc->countersTable='metrix_counters';
        $chartCalc->counterModel=MetrixCounter::className();
        $chartCalc->modemModel=ModemStatus::className();
        $chartCalc->indicationsModel=MetrixIndication::className();
        $chartCalc->counter_id = $id;
        $chartCalc->beginDate =date("Y-m-01");
        $chartCalc->endDate =date("Y-m-t");


        $label = $chartCalc->graph();
        $monthTempArray = $chartCalc->tempChart();

        $labels=[];
        $labelsTemp=[];

        for($i=0;$i<count($label);$i++){

            $labelArr=explode('-',$label[$i]["label"]);
            if(isset($labelArr[2])) {
                $labels["labels"][] = $labelArr[2];
            }else{
                $labels["labels"][] =$labelArr[0];
            }
            $labels["data"][]=$label[$i]["data"][0];
        }

        for($i=0;$i<count($monthTempArray)-1;$i++){
            $labelArr=explode('-',$monthTempArray[$i]["label"]);
            $labelsTemp["labels"][]=$labelArr[1];
            $labelsTemp["data"][]=$monthTempArray[$i]["data"][0];
        }

        $oDU=$monthTempArray[$i];



        $globalChartSettings = ['animation' => 'false', 'tooltipTemplate' => '"<%= value %> куб."'];




        echo $this->render('view', [
            'url'=>'/metrix/counters/',
            'id'=>$id,
            'counter' => $counter,
            'userRoles'=>$userRoles,
            'globalChartSettings' => $globalChartSettings,
            'labels'=>$labels,
            'labelsTemp'=>$labelsTemp,
            "oDU"=>$oDU,
        ]);
    }

}