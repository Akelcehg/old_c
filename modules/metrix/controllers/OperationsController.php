<?php

namespace app\modules\metrix\controllers;

use app\components\ChartCalc;
use app\models\Language;
use app\models\ModemStatus;
use app\models\SimCard;
use app\models\User;
use app\modules\admin\components\CounterEvents;
use app\modules\metrix\components\MetrixCommandGenerator;
use app\modules\metrix\components\MetrixValveButtonWidget;
use app\modules\metrix\models\MetrixCounter;
use app\modules\metrix\models\MetrixIndication;
use app\modules\metrix\models\MetrixModemStatus;
use app\modules\metrix\models\MetrixSimCard;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use Yii;

class OperationsController extends Controller
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
                                'open',
                                'close',
                                'refresh',
                                'ajaxmetrixtocalendar',
                                'ajaxcountertochartbyday',
                                'ajaxcountertochartbyweek',
                                'ajaxcountertochart',
                                'ajaxcountertoconsumtiondetailbyweek',
                                'ajaxcountertoconsumtiondetail',
                                'getcreateeventform'


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

    public function actionRefresh(){
        $id=Yii::$app->request->get('id',false);
        $lang=Yii::$app->request->get('lang',false);
        Language::setCurrent($lang);

        if(!empty($id)) {

            return MetrixValveButtonWidget::widget(['id'=>$id]);
        }

    }


    public function actionOpen(){
        $id=Yii::$app->request->get('id',false);

        if(!empty($id)) {
            $mcg = new MetrixCommandGenerator();
            $mcg->counter_id = $id;
            $mcg->OpenValveWithLeakCheck();

            return MetrixValveButtonWidget::widget(['id'=>$id]);
        }

    }

    public function actionClose(){

        $id=Yii::$app->request->get('id',false);

        if(!empty($id)) {
            $mcg = new MetrixCommandGenerator();
            $mcg->counter_id = $id;
            $mcg->CloseValve();

            return MetrixValveButtonWidget::widget(['id'=>$id]);
        }
    }

    public function actionAjaxmetrixtocalendar()
    {
        $counterEvents=new CounterEvents();
        Yii::$app->response->format = 'json';
        return $counterEvents->MetrixToCalendar();
    }


    public function actionAjaxcountertochartbyday()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);
        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counterModel=MetrixCounter::className();
        $chartCalc->modemModel=MetrixModemStatus::className();
        $chartCalc->indicationsModel=MetrixIndication::className();
        $chartCalc->countersTable='metrix_counters';
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $label = $chartCalc->chartByDay();
        Yii::$app->response->format = 'json';
        return $label;
    }


    public function actionAjaxcountertochartbyweek()
    {
        $counter_id = Yii::$app->request->post('counter_id', null);

        $data = Yii::$app->request->post('data', 0);


        $chartCalc = new ChartCalc();
        $chartCalc->counterModel=MetrixCounter::className();
        $chartCalc->modemModel=MetrixModemStatus::className();
        $chartCalc->indicationsModel=MetrixIndication::className();
        $chartCalc->countersTable='metrix_counters';
        $chartCalc->counter_id = $counter_id;
        $chartCalc->data = $data;

        $output = $chartCalc->chartByWeek();

        Yii::$app->response->format = 'json';
        return $output;
    }

    public function actionAjaxcountertochart()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counterModel=MetrixCounter::className();
        $chartCalc->modemModel=ModemStatus::className();
        $chartCalc->indicationsModel=MetrixIndication::className();
        $chartCalc->countersTable='metrix_counters';
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $label = $chartCalc->graph();
        Yii::$app->response->format = 'json';
        return $label;
    }




    public function actionAjaxcountertoconsumtiondetail()
    {


        $counter_id = Yii::$app->request->get('counter_id', 0);
        $beginData = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);
        $lang = Yii::$app->request->get('lang', 0);
        Language::setCurrent($lang);

        $flatCounters = MetrixCounter::find()->where(['id' => $counter_id]);

        if ( User::is('user')) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' => Yii::$app->user->id]);
        }

        $flatCountersAll = $flatCounters->all();

        $indications = 0;
        $counter = new MetrixCounter();

        $last = $counter->getLastFlatIndications($counter_id);
        $first = $counter->getFirstFlatIndications($counter_id);
        foreach ($flatCountersAll as $counter) {
            $indications += $counter->flatindications;
        }

        $srsut='';

        $dt=new \DateTime($beginData);

        if($beginData != $endDate) {
            if($dt->format("n")==date("n")) {
                $srsut = '<span id="srsut" style="font-size:20px;float:right;font-size:20px;width:375px">'.Yii::t('metrix','ConsumpDayAverage').' : ' . round($indications / date("d"), 3) . '</span>';
            }else{
                $srsut = '<span id="srsut" style="font-size:20px;float:right;font-size:20px;width:375px">'.Yii::t('metrix','ConsumpDayAverage').' : ' . round($indications / $dt->format("t"), 3) . '</span>';
            }
        }



        $line="<table id='consumDetail' class='table-striped table-hover table-bordered'>";
        $line.="<tr>";
        $line.="<td>".Yii::t('metrix','period_begin').":" . $first . "</td>";
        $line.="<td>".Yii::t('metrix','period_end').":" . $last . "</td>";
        $line.="<td id='consumtionSumm'>".Yii::t('metrix','consump').":" . round($indications, 3) . "</td>";
        $line.="</tr>";
        $line.="</table>";
        return $line;
    }

    public function actionAjaxcountertoconsumtiondetailbyweek()
    {

        $counter_id = Yii::$app->request->post('counter_id', 0);
        $data = Yii::$app->request->post('data', 0);
        $lang = Yii::$app->request->post('lang', 0);
        Language::setCurrent($lang);

        $line = '';

        $line.="<table id='consumDetail' class='table-striped table-hover table-bordered' height='207px'>";
        for ($i = 1; $i <= 7; $i++) {

            $last = MetrixCounter::getLastFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
            $first = MetrixCounter::getFirstFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
            $indication = $last - $first;
            $line.="<tr>";

            $day=Yii::$app->formatter->asDate($data[$i-1],'EEEE');
            if($day==2){
                $day='ორშაბათი';
            }

            switch ($i) {
                case 1:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(255,0,0,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(255,0,0,0.5)' ></i></td><td>".$day."</td>";
                    break;
                case 2:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(255,165,0,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(255,165,0,0.5)' ></td><td>".$day."</td>";
                    break;
                case 3:
                    $title = "<td><i class='fa fa-stop' style='color:rgb(255, 85, 253)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(255, 85, 253, 0.5)' ></td><td> ".$day." </td>";
                    break;
                case 4:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(0,139,0,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(0,139,0,0.5)' ></td><td> ".$day." </td>";
                    break;
                case 5:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(135,206,255,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(135,206,255,0.5)' ></td><td>".$day." </td>";
                    break;
                case 6:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(0,0,255,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(0,0,255,0.5)' ></td><td>".$day."</td>";
                    break;
                case 7:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(85,26,139,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(85,26,139,0.5)' ></td><td>".$day."</td>";
                    break;
            }
            $line .=
                $title .
                '<td>'.$data[$i-1].'</td>'
                . '<td>'.Yii::t('metrix','period_begin').':' . $first . '</td>'
                . '<td>'.Yii::t('metrix','period_end').':' . $last . ' </td>'
                . '<td id="consumtionSumm">'.Yii::t('metrix','consump').':' . round($indication, 3) . '</td>';


            $line.="</tr>";
        }
        $line.="</table>";


        /* for ($i = 1; $i <= 7; $i++) {

             $last = Counter::getLastFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
             $first = Counter::getFirstFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
             $indication = $last - $first;



             switch ($i) {
                 case 1:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(255,0,0,1)' ></i><i class='fa fa-stop' style='color:rgba(255,0,0,0.5)' ></i> Понедельник </span>";
                     break;
                 case 2:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(255,165,0,1)' ></i><i class='fa fa-stop' style='color:rgba(255,165,0,0.5)' ></i> Вторник</span>";
                     break;
                 case 3:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(255,255,0,1)' ></i><i class='fa fa-stop' style='color:rgba(255,255,0,0.5)' ></i> Среда </span>";
                     break;
                 case 4:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(0,139,0,1)' ></i><i class='fa fa-stop' style='color:rgba(0,139,0,0.5)' ></i>  Четверг </span>";
                     break;
                 case 5:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(135,206,255,1)' ></i><i class='fa fa-stop' style='color:rgba(135,206,255,0.5)' ></i> Пятница </span>";
                     break;
                 case 6:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(0,0,255,1)' ></i><i class='fa fa-stop' style='color:rgba(0,0,255,0.5)' ></i>Суббота  </span>";
                     break;
                 case 7:
                     $title = "<span style='text-align:left;font-size:20px;float:left;width:200px'><i class='fa fa-stop' style='color:rgba(85,26,139,1)' ></i><i class='fa fa-stop' style='color:rgba(85,26,139,0.5)' ></i> Воскресенье  </span>";
                     break;
             }
             $line .=
                 '<div style="clear:both;text-align:center;">'
                 .'<span style=\'text-align:left;font-size:20px;float:left;width:100px\'>'.$data[$i-1].'</span>'
                 . '<span style="float:left;cursor: pointer;font-size:20px" id="begin">' . $title . ' Начало периода:' . $first . '</span>'
                 . '<span id="end" style="font-size:20px; ">Конец периода:' . $last . ' </span>'
                 . '<span style="float:right;width:200px">&nbsp;<span style="cursor: pointer;font-size:20px;color:rgba(255,0,0,1)" id="consumtionSumm"> Расход:' . round($indication, 3) . '</span></span>'
                 . '</div>';
         }*/
        return $line;
    }

    function actionGetcreateeventform(){
        $this->layout = 'onlyGrid';
       return $this->render('CreateEventForm');
    }

}