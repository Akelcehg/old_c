<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChartController
 *
 * @author alks
 */

namespace app\modules\admin\controllers;

use app\components\RightsComponent;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use Yii;
use app\models\User;
use app\models\Counter;
use yii\filters\AccessControl;
use yii\db\Query;
use app\models\UserIndications;
use app\models\Regions;
use app\models\CounterAddress;
use app\components\CounterQuery;
use app\components\Alerts;
use app\models\CounterAddressSearch;
use app\models\UserModems;
use app\models\UserCountersSearch;
use app\models\UserModemTemparatues;
use app\models\Address;
use app\models\AddressSearch;
use app\components\ChartCalc;

class ChartController extends Controller
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
                                'index',
                                'graph',
                                'ajaxcountertochart',
                                'ajaxcountertotempchart',
                                'ajaxcountertochartbyday',
                                'ajaxcountertemptochartbyday',
                                'ajaxcountertochartbyweek',
                                'ajaxcountertotempchartbyweek',
                                'ajaxcountertoconsumtiondetailbyweek',
                                'ajaxcountertoconsumtiondetail',
                                'ajaxuploadimage',
                                'exportexcel',
                                'exportimage'
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'regionWatcher', 'waterWatcher', 'user'],
                        ]
                        ,
                        [
                            'actions' => [
                                'addcounter',
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

    public function actionGraph()
    {

        /*
        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);
        $city = Yii::$app->request->get('city', 0);
        $geoId = Yii::$app->request->get('geo_location_id', 0);

        $address = Address::find();
        $address->andWhere(['status'=>'ACTIVE']);
        $address = CounterQuery::counterQueryByRoleAndAddress($address);


        if (!User::is('admin')) {
            $currentUser = new User();
            $address->andWhere('address.id = :geo_location_id', [':geo_location_id'
            => $currentUser->getCurrentUserRegionId(Yii::$app->user->id)
            ]);
        } else {
            if ($geoId) {
                $address->andWhere('address.id = :geo_location_id', [':geo_location_id' => $geoId]);
            }

            if ($city) {
                $address->andWhere('address.region_id = :region_id', [':region_id' => $city]);
            }
        }


        $alerts = new Alerts(CounterQuery::counterQueryByRole()
            ->andWhere('counters.serial_number IS NOT NULL')
            ->all());


        $dataProvider = new ActiveDataProvider([
            'query' => $address,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);


        $searchModel = new AddressSearch();

        if (Yii::$app->request->isAjax) {

            $searchModel->id = Yii::$app->request->post('id', 0);
            $searchModel->endDate = Yii::$app->request->post('endDate', date('Y-M-d'));
            $searchModel->beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $searchModel->leak = Yii::$app->request->post('leak', 0);
            $searchModel->status = 'ACTIVE';
            $searchModel->tamper = Yii::$app->request->post('tamper', 0);
            $searchModel->magnet = Yii::$app->request->post('magnet', 0);
            $searchModel->disconnect = Yii::$app->request->post('disconnect', 0);
            $searchModel->lowBatteryLevel = Yii::$app->request->post('lowBatteryLevel', 0);

            $rights = new  RightsComponent();
            $searchModel = $rights->updateSearchModelCounter($searchModel);
        }

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
*/

        $address = new \app\modules\admin\components\Counter();
        $address->CounterAddressList();


        //disable chart animation
        $globalChartSettings = [
            'animation' => 'false',
            'tooltipTemplate' => '"<%= value %> куб."',
        ];

        if (Yii::$app->request->isAjax) {

            return $this->renderAjax('_addressListAjax', [
                'dataProvider' => $address->getDataProvider(),
                'address' => $address->getModel(),
                //'alerts' => $alerts,
                'searchModel' => $address->getSearchModel(),
                'globalChartSettings' => $globalChartSettings,
            ]);
        }




        return $this->render('graph', [
            'dataProvider' =>$address->getDataProvider(),
            'address' =>  $address->getModel(),
            //'alerts' => $alerts,
            'searchModel' => $address->getSearchModel(),
            'globalChartSettings' => $globalChartSettings,
        ]);
    }



    public function actionAjaxcountertochartbyday()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);
        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $label = $chartCalc->chartByDay();
        Yii::$app->response->format = 'json';
        return $label;
    }

    public function actionAjaxcountertemptochartbyday()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $allconsumptionArray = $chartCalc->tempChartByDay();

        Yii::$app->response->format = 'json';
        return $allconsumptionArray;
    }



    public function actionAjaxcountertochartbyweek()
    {
        $counter_id = Yii::$app->request->post('counter_id', null);

        $data = Yii::$app->request->post('data', 0);


        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->data = $data;

        $output = $chartCalc->chartByWeek();

        Yii::$app->response->format = 'json';
        return $output;
    }

    public function actionAjaxcountertotempchartbyweek()
    {
        $counter_id = Yii::$app->request->post('counter_id', null);

        $data = Yii::$app->request->post('data', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->data = $data;

        $output = $chartCalc->tempChartByWeek();

        Yii::$app->response->format = 'json';
        return $output;
    }


    public function actionAjaxcountertochart()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $label = $chartCalc->graph();
        Yii::$app->response->format = 'json';
        return $label;
    }


    public function actionAjaxcountertotempchart()
    {
        $counter_id = Yii::$app->request->get('counter_id', null);

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;
        $monthTempArray = $chartCalc->tempChart();
        Yii::$app->response->format = 'json';
        return $monthTempArray;
    }

        public function actionAjaxcountertoconsumtiondetail()
    {


        $counter_id = Yii::$app->request->get('counter_id', 0);
        $beginData = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $flatCounters = Counter::find()->where(['id' => $counter_id]);

        if ( User::is('user')) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' => Yii::$app->user->id]);
        }

        $flatCountersAll = $flatCounters->all();

        $indications = 0;
        $counter = new Counter();

        $last = $counter->getLastFlatIndications($counter_id);
        $first = $counter->getFirstFlatIndications($counter_id);
        foreach ($flatCountersAll as $counter) {
            $indications += $counter->flatindications;
        }

        $srsut='';

        $dt=new \DateTime($beginData);

        if($beginData != $endDate) {
            if($dt->format("n")==date("n")) {
                $srsut = '<span id="srsut" style="font-size:20px;float:right;font-size:20px;width:375px">Среднесуточный расход : ' . round($indications / date("d"), 3) . '</span>';
            }else{
                $srsut = '<span id="srsut" style="font-size:20px;float:right;font-size:20px;width:375px">Среднесуточный расход : ' . round($indications / $dt->format("t"), 3) . '</span>';
            }
        }

        // Yii::$app->response->format = 'json';
        //return['first'=> $first,'last'=>$last,'consumption'=>round($indications,3)];

       /* $line = '<div style="clear:both;text-align:center;">'

            . '<span style="float:left;cursor: pointer;font-size:20px" id="begin">Начало периода:' . $first . '</span>'
            . '<span id="end" style="font-size:20px; ">Конец периода:' . $last . ' </span>'
            . '<span style="float:right;width:200px">&nbsp;<span style="cursor: pointer;font-size:20px;font-weight: 800;color:rgba(255,0,0,1) " id="consumtionSumm"> Расход:' . round($indications, 3) . '</span></span></br>'
            .$srsut
            . '</div>';*/

        $line="<table id='consumDetail' class='table-striped table-hover table-bordered'>";
        $line.="<tr>";
        $line.="<td>Начало периода:" . $first . "</td>";
        $line.="<td>Конец периода:" . $last . "</td>";
        $line.="<td id='consumtionSumm'>Расход:" . round($indications, 3) . "</td>";
        $line.="</tr>";
        $line.="</table>";
        return $line;
    }

    public function actionAjaxcountertoconsumtiondetailbyweek()
    {

        $counter_id = Yii::$app->request->post('counter_id', 0);
        $data = Yii::$app->request->post('data', 0);

        $line = '';

        $line.="<table id='consumDetail' class='table-striped table-hover table-bordered' height='207px'>";
        for ($i = 1; $i <= 7; $i++) {

            $last = Counter::getLastFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
            $first = Counter::getFirstFlatIndicationsStatic($counter_id, $data[$i - 1], $data[$i]);
            $indication = $last - $first;
            $line.="<tr>";


            switch ($i) {
                case 1:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(255,0,0,1)' ></i></td>
                                <td><i class='fa fa-stop' style='color:rgba(255,0,0,0.5)' ></i></td><td> Понедельник </td>";
                    break;
                case 2:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(255,165,0,1)' ></i></td><td><i class='fa fa-stop' style='color:rgba(255,165,0,0.5)' ></td><td> Вторник</td>";
                    break;
                case 3:
                    $title = "<td><i class='fa fa-stop' style='color:rgb(255, 85, 253)' ></i></td><td><i class='fa fa-stop' style='color:rgba(255, 85, 253, 0.5)' ></td><td> Среда </td>";
                    break;
                case 4:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(0,139,0,1)' ></i></td><td><i class='fa fa-stop' style='color:rgba(0,139,0,0.5)' ></td><td>   Четверг </td>";
                    break;
                case 5:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(135,206,255,1)' ></i></td><td><i class='fa fa-stop' style='color:rgba(135,206,255,0.5)' ></td><td> Пятница </td>";
                    break;
                case 6:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(0,0,255,1)' ></i></td><td><i class='fa fa-stop' style='color:rgba(0,0,255,0.5)' ></td><td>Суббота  </td>";
                    break;
                case 7:
                    $title = "<td><i class='fa fa-stop' style='color:rgba(85,26,139,1)' ></i></td><td><i class='fa fa-stop' style='color:rgba(85,26,139,0.5)' ></td><td> Воскресенье  </td>";
                    break;
            }
            $line .=
                 $title .
                '<td>'.$data[$i-1].'</td>'
                . '<td>Начало периода:' . $first . '</td>'
                . '<td>Конец периода:' . $last . ' </td>'
                . '<td id="consumtionSumm"> Расход:' . round($indication, 3) . '</td>';


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

    public function actionExportimage($temp)
    {
        $file = 'images/' . $temp . '.png';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // читаем файл и отправляем его пользователю
            readfile($file);

            unlink($file);
            exit;
        }
    }

    public function actionAjaxuploadimage()
    {

        $img = Yii::$app->request->post('image', false);


        define('UPLOAD_DIR', 'images/');

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = uniqid();
        $file = UPLOAD_DIR . $filename . '.png';
        file_put_contents($file, $data);
        return $filename;
    }

}
