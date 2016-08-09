<?php

namespace app\modules\admin\controllers;

use app\components\Prom\Prom;
use app\components\FlouTechCommandGenerator;
use app\components\FlouTechReportGenerator;
use app\models\CommandConveyor;
use app\models\CorrectorsData;
use app\models\CorrectorToCounter;
use app\models\DateTime;
use app\models\DayData;
use app\models\Diagnostic;
use app\models\EmergencySign;
use app\models\EmergencySituation;
use app\models\FloutechCommands;
use app\models\FloutechVariables;
use app\models\HourData;
use app\models\Indication;
use app\models\Intervention;
use app\models\MomentData;
use app\models\Name;
use app\models\Search;
use app\models\StaticDataGeneral;
use app\models\StaticDataHard;
use app\models\StaticDataSensor;
use app\modules\admin\components\Correctors;
use app\modules\admin\components\Counter;
use kartik\mpdf\Pdf;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use app\models\Address;
use app\models\AddressSearch;
use Yii;
use app\components\RightsComponent;

use yii\filters\AccessControl;



class DefaultController extends Controller
{

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
                                'onas',
                                'correctors',
                                'getvavilova',
                                'autocomplete',
                                'gettxt',
                                'getmonthtxt',
                                'gettxtasweb',
                                'generate',
                                'getem',
                                'new',
                                'new2',
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'PromAdmin'],
                        ]
                        ,

                    ]
                ,
            ],
        ];
    }


    public function actionIndex()
    {
        //return $this->render('index');

        $counterId=Yii::$app->request->get('counterId');

        if(9>date("H")){
            $date=Yii::$app->request->get('date',date("Y-m-d",time()-3600*24*2));
        }else{
            $date=Yii::$app->request->get('date',date("Y-m-d",time()-3600*24));
        }

        $type=Yii::$app->request->get('type','day');
        $format=Yii::$app->request->get('format','pdf');

        $prom=new Prom();
        $prom->id=$counterId;
        $prom->date=$date;
        $prom->type=$type;
        $prom->format=$format;
        return $prom->GetReport();
    }
    
     public function actionOnas()
    {
        $this->layout='onlyGrid';
         
        return $this->render('onas');
    }

    public function actionCorrectors()
    {
        $this->layout='smartAdmin';

        $data=CorrectorToCounter::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$data,
            'sort' => [
                'attributes' => ['flat'],
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $globalChartSettings = ['responsive' =>'false','animation' => 'false','showTooltips'=>'false','tooltipTemplate' => '"<%= value %> куб."'];


        return $this->render('correctors',[
            'dataProvider' => $dataProvider,
            'globalChartSettings'=>$globalChartSettings,

        ]);


    }

    public function actionGetvavilova()
    {
        $this->layout='onlyGrid';
        $address = new Counter();
        $address->CounterList();

        return $this->render('vavilova',[
            'dataProvider' => $address->getDataProvider(),
            'searchModel'=>$address->getSearchModel()

        ]);



    }



    public function actionGetdata()
    {

        $ind=Indication::find()->where(['counter_id'=>'454'])->limit(90)->orderBy(['created_at'=>SORT_DESC])->all();

        foreach($ind as $i)
        {
            echo $i->indications.',';
        }

    }




    public function actionGettxtasweb()
    {
        $this->layout='onlyGrid';
        $counterId=Yii::$app->request->get('counterId');
        $date=Yii::$app->request->get('date',date("Y-m-d H:i:s"));

        $dateArr=explode(" ",$date);
        $dateDetail=explode("-",$dateArr[0]);
       // $timeDetail=explode(":",$dateArr[1]);

        //print_r($dateDetail);

        $counter=\app\models\CorrectorToCounter::find()->where(['counter_id'=>$counterId])->one();

        $dateTime=DateTime::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $name=Name::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticHard=StaticDataHard::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticSensor=StaticDataSensor::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticGeneral=StaticDataGeneral::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $momentData=MomentData::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();

        $hourData=HourData::find()->where('hour_n >=:hour_n AND all_id=:all_id AND month=:month AND year=:year AND day=:day',[":hour_n"=>9,':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0],':day'=>$dateDetail[2]])
            ->orWhere('hour_n <:hour_n1 AND all_id=:all_id1 AND month=:month1 AND year=:year1 AND day=:day1',[":hour_n1"=>9,':all_id1'=>$counterId,':month1'=>$dateDetail[1],':year1'=>$dateDetail[0],':day1'=>$dateDetail[2]+1])->limit(24)->orderBy(['id'=>SORT_DESC])->all();




        $dayData=DayData::find()->where(['all_id'=>$counterId,'month'=>$dateDetail[1],'year'=>$dateDetail[0],'day'=>$dateDetail[2]])->orderBy(['id'=>SORT_DESC])->one();










        $hourData=array_reverse($hourData);

        //print_r($hourData);

        if($counterId=="999") {
            return $this->render("otchet1", [
                'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                'programName' => "ASER xx.xxx",
                'time' => $dateTime->time,
                'date' => $dateTime->date,
                'correctorName' => $name->corrector_name,
                'serialNumber' => $staticGeneral->zavod_number,
                'complexName' => $staticGeneral->complex_name,
                'tubeName' => $name->tube_name,
                'poVersion' => $name->version,
                'metodName' => 'NX19 мод',
                'counterName' => $staticSensor->counter_name,
                'metodDimName' => "Счетчик",
                'contractTime' => "9:00",
                'density' => round($staticHard->density, 3),
                'molCO2' => round($staticHard->mol_co2, 2),
                'pressureType' => "Абсолютное",
                'molN2' => round($staticHard->mol_n2, 2),
                'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                'impulseOnM3' => "10.00",
                'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                'qStop' => round($staticSensor->qstop, 4),
                'hours' => $hourData,

                'sumRC' => round($dayData->v_rc, 2),
                'sumSC' => round($dayData->v_sc, 2),
                'bezavV' => round($dayData->v_rc, 2),
                'avV' => 0.0,
                'polV' => round($dayData->v_rc, 2),
                'bezavVsu' => round($dayData->v_sc, 2),
                'polVsu' => round($dayData->v_sc, 1),
                'avVsu' => 0.0,
                'polVsu' => round($dayData->v_sc, 1),
                'timeQem' => $dayData->time_emerg,
                'pokaz' => round($dayData->vpokaz_rc, 2),
                'timeEmrg' => $dayData->time_emerg2,


            ]);
        }
        else{

          return $this->render("otchet1p", [
                'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                'programName' => "ASER xx.xxx",
                'time' => $dateTime->time,
                'date' => $dateTime->date,
                'correctorName' => $name->corrector_name,
                'serialNumber' => $staticGeneral->zavod_number,
                'complexName' => $staticGeneral->complex_name,
                'tubeName' => $name->tube_name,
                'poVersion' => $name->version,
                'metodName' => 'ГОСТ8.586-NX19 мод',
                /// 'counterName' => $staticSensor->counter_name,
                'metodDimName' => "Перепад на СУ",
                'contractTime' => "9:00",
                'density' => round($staticHard->density, 3),
                'molCO2' => round($staticHard->mol_co2, 2),
                'pressureType' => "Абсолютное",
                'molN2' => round($staticHard->mol_n2, 2),
                'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                'impulseOnM3' => "10.00",
                'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                'qStop' => round($staticSensor->qstop, 4),
                'hours' => $hourData,

                'typeOtb'=>"Угловой",
                'Dtube'=>round($staticHard->d_tube,3),
                'Dsu'=>round($staticHard->d_sug_device,3),
                'otsechka'=>round($staticHard->otsechka,4),
                'sharpness'=>round($staticHard->sharpness,5),
                'a0'=>"10.7",
                'a0su'=>"15.5",
                'a1'=>"12.0",
                'a1su'=>"10.5",
                'a2'=>"0.0",
                'a2su'=>"0.0",
                'Dvyaz'=>"1.06275e-06",
                'perepNPI' => round(10.000, 3),
                'perepVPI' => round(4001.0, 1),
                "mki"=>1,
                "Rzkrug"=>0.04,
                'sumRC' => round($dayData->v_rc, 2),
                'sumSC' => $dayData->v_sc,
                'bezavV' => round($dayData->v_rc, 2),
                'avV' => 0.0,
                'polV' => round($dayData->v_rc, 2),
                'bezavVsu' => round($dayData->v_sc, 2),
                'polVsu' => round($dayData->v_sc, 1),
                'avVsu' => 0.0,
                'polVsu' => round($dayData->v_sc, 1),
                'timeQem' => $dayData->time_emerg,
                'pokaz' => round($dayData->vpokaz_rc, 2),
                'timeEmrg' => $dayData->time_emerg2,


            ]);
        }


    }


    public function actionGettxt2()
    {

            $counterId=Yii::$app->request->get('counterId');

            if(9>date("H")){
                $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24*2));
            }else{
                $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24));
            }




            $dateArr=explode(" ",$date);
            $dateDetail=explode("-",$dateArr[0]);

            $counter=\app\models\CorrectorToCounter::find()->where(['id'=>$counterId])->one();

            $dateTime=DateTime::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            $name=Name::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            $staticHard=StaticDataHard::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            $staticSensor=StaticDataSensor::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            $staticGeneral=StaticDataGeneral::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            $momentData=MomentData::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
            //$hourDataA=HourData::find()->where(['all_id'=>$counterId])->limit(24)->orderBy(['created_at'=>SORT_DESC,'day'=>SORT_ASC,'hour_n'=>SORT_ASC])->asArray()->all();
           // $hourData=HourData::find()->where(['all_id'=>$counterId])->limit(24)->orderBy(['id'=>SORT_DESC])->all();
       /* $hourData=HourData::find()->where('hour_n >=:hour_n AND all_id=:all_id AND month=:month AND year=:year AND day=:day',[":hour_n"=>9,':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0],':day'=>$dateDetail[2]])
            ->orWhere('hour_n <:hour_n1 AND all_id=:all_id1 AND month=:month1 AND year=:year1 AND day=:day1',[":hour_n1"=>9,':all_id1'=>$counterId,':month1'=>$dateDetail[1],':year1'=>$dateDetail[0],':day1'=>$dateDetail[2]+1])->limit(24)->orderBy(['id'=>SORT_DESC])->all();
*/
        $fTRG= new FlouTechReportGenerator();
        $fTRG->counter_id=$counterId;
        $hourData=$fTRG->dayHourReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);

            $correctorData=CorrectorsData::findOne(["all_id"=>$counterId]);


       // $dayData=DayData::find()->where(['all_id'=>$counterId,'month'=>$dateDetail[1],'year'=>$dateDetail[0],'day'=>$dateDetail[2]])->orderBy(['id'=>SORT_DESC])->one();

        $dayData=$fTRG->dayDayReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);
        //$hourData=array_reverse($hourData);

        $intervention=Intervention::find()->where('hour >=:hour_n AND all_id=:all_id AND month=:month AND year=:year AND day=:day',[":hour_n"=>9,':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0],':day'=>$dateDetail[2]])
            ->orWhere('hour <:hour_n1 AND all_id=:all_id1 AND month=:month1 AND year=:year1 AND day=:day1',[":hour_n1"=>9,':all_id1'=>$counterId,':month1'=>$dateDetail[1],':year1'=>$dateDetail[0],':day1'=>$dateDetail[2]+1])->orderBy(['id'=>SORT_DESC])->all();


        if(count($hourData)>=24){

        if($correctorData->measured_value=="Quantity") {
            $content = $this->renderPartial("otchet", [
                'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                'programName' => "ASER ".Yii::$app->params['version'],
                'time' => $dateTime->time,
                'date' => $dateTime->date,
                'correctorName' => $name->corrector_name,
                'serialNumber' => $staticGeneral->zavod_number,
                'complexName' => $staticGeneral->complex_name,
                'tubeName' => $name->tube_name,
                'poVersion' => $name->version,
                'metodName' => 'NX19 мод',
                'counterName' => $staticSensor->counter_name,
                'metodDimName' => "Счетчик",
                'contractTime' => "9:00",
                'density' => round($staticHard->density, 3),
                'molCO2' => round($staticHard->mol_co2, 2),
                'pressureType' => "Абсолютное",
                'molN2' => round($staticHard->mol_n2, 2),
                'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                'impulseOnM3' => "10.00",
                'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                'qStop' => round($staticSensor->qstop, 4),
                'hours' => $hourData,
                'intervention'=>$intervention,

                'sumRC' => round($dayData->v_rc, 2),
                'sumSC' => round($dayData->v_sc, 2),
                'bezavV' => round($dayData->v_rc, 2),
                'avV' => 0.0,
                'polV' => round($dayData->v_rc, 2),
                'bezavVsu' => round($dayData->v_sc, 2),
                'polVsu' => round($dayData->v_sc+$dayData->vav_sc, 1),
                'avVsu' => round($dayData->vav_sc, 1),
                'polVsu' => round($dayData->v_sc, 1),
                'timeQem' =>  $dayData->time_emerg2,
                'pokaz' => round($dayData->vpokaz_rc, 2),
                'timeEmrg' =>$dayData->time_emerg,


            ]);
        }
        else{

            $content = $this->renderPartial("otchetp", [
                'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                'programName' =>"ASER ". Yii::$app->params['version'],
                'time' => $dateTime->time,
                'date' => $dateTime->date,
                'correctorName' => $name->corrector_name,
                'serialNumber' => $staticGeneral->zavod_number,
                'complexName' => $staticGeneral->complex_name,
                'tubeName' => $name->tube_name,
                'poVersion' => $name->version,
                'metodName' => 'ГОСТ8.586-NX19 мод',
               /// 'counterName' => $staticSensor->counter_name,
                'metodDimName' => "Перепад на СУ",
                'contractTime' => "9:00",
                'density' => round($staticHard->density, 3),
                'molCO2' => round($staticHard->mol_co2, 2),
                'pressureType' => "Абсолютное",
                'molN2' => round($staticHard->mol_n2, 2),
                'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                'impulseOnM3' => "10.00",
                'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                'qStop' => round($staticSensor->qstop, 4),
                'hours' => $hourData,
                'intervention'=>$intervention,
                'typeOtb'=>"Угловой",
                'Dtube'=>round($staticHard->d_tube,3),
                'Dsu'=>round($staticHard->d_sug_device,3),
                'otsechka'=>round($staticHard->otsechka,4),
                'sharpness'=>round($staticHard->sharpness,5),
                'a0'=>"10.7",
                'a0su'=>"15.5",
                'a1'=>"12.0",
                'a1su'=>"10.5",
                'a2'=>"0.0",
                'a2su'=>"0.0",
                'Dvyaz'=>"1.06275e-06",
                'perepNPI' => round(10.000, 3),
                'perepVPI' => round(4001.0, 1),
                "mki"=>round($staticHard->control_interval,0),
                "Rzkrug"=>round($staticHard->radius_diafr, 2),
                'sumRC' => round($dayData->v_rc, 2),
                'sumSC' => round($dayData->v_sc, 2),
                'bezavV' => round($dayData->v_rc, 2),
                'avV' => 0.0,
                'polV' => round($dayData->v_rc, 2),
                'bezavVsu' => round($dayData->v_sc, 2),
                'polVsu' => round($dayData->v_sc+$dayData->vav_sc, 1),
                'avVsu' => round($dayData->vav_sc, 1),
                'polVsu' => round($dayData->v_sc, 1),
                'timeQem' => $dayData->time_emerg2,
                'pokaz' => round($dayData->vpokaz_rc, 2),
                'timeEmrg' => $dayData->time_emerg,


            ]);
        }}else
        {
            $content="Отчет за ".$date." будет сформирован после 9:00";
        }


        //echo $result;

        //$content = $this->renderPartial('_reportView');

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename'=>$counter->address->fulladdress."-".$hourData[0]->day . ' ' . $hourData[0]->month . ' 20' . $hourData[0]->year .'.pdf',

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => [ 'SetFooter'=>["Лист {PAGENO} из 2","RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline=0;


        return $pdf->render();

    }

    function getMonth($a){
        switch($a){
            case 1 :return "января";break;
            case 2 :return "февраля";break;
            case 3 :return "марта";break;
            case 4 :return "апреля";break;
            case 5 :return "мая";break;
            case 6 :return "июня";break;
            case 7 :return "июля";break;
            case 8 :return "августа";break;
            case 9 :return "сентября";break;
            case 10 :return "октября";break;
            case 11 :return "ноября";break;
            case 12 :return "декабря";break;
        }
    }



    function text(){


        $fp = fopen('counter.txt', 'w+');

        fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%50s",'');fprintf($fp,"%s\n","Коммерческий отчет");
        fprintf($fp,"\n");
        fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%20s",'');fprintf($fp,"%s\n","СУТОЧНЫЙ ОТЧЕТ");
        fprintf($fp,"%20s",'');fprintf($fp,"%18s",'');fprintf($fp,"%s\n"," за 31 января 2016 года");
        fprintf($fp,"%23s",'');fprintf($fp,"%s\n","Составлен программой ASER х.ххх по  даннім на 10:33:00 02.02.16");
        fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s","Корректор ПК-3к N 157:3-234");fprintf($fp,"%10s",'');fprintf($fp,"%s \n","Т/п 1: Бпивденный Мих44");
        fprintf($fp,"%20s",'');fprintf($fp,"%s","Версия ПО: Nov 2 2009 15:01:09");fprintf($fp,"\n");
        fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s","Метод расчета");fprintf($fp,"%'.9s","");fprintf($fp,"%s","ГОСТ8.563 (Ксж по NX19 мод.)");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Метод измерений");fprintf($fp,"%'.7s","");fprintf($fp,"%s","Счетчик");
        fprintf($fp,"%10s",'');fprintf($fp,"%s","Контрактный час , ЧЧ:00");fprintf($fp," %s","9:00");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Плотность , кг/м3");fprintf($fp,"%'.6s","");fprintf($fp,"%s","0.735");
        fprintf($fp,"%11s",'');fprintf($fp,"%s","Молярная доля СО2,%");fprintf($fp,"%s","0.2");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Тип давления");fprintf($fp,"%'.10s","");fprintf($fp,"%s","Абсолютное");
        fprintf($fp,"%7s",'');fprintf($fp,"%s","Молярная доля N2,%");fprintf($fp,"%s","0.7");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s %s","Коэффицент сжимаемости","0.99986");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.23s %s","Количество импульсов счетчика на 1м3","","10.000");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","НПИ давления, кгс/см2");fprintf($fp,"%'.0s","");fprintf($fp," %s","0.8564");
        fprintf($fp,"%11s",'');fprintf($fp,"%s","ВПИ давления, кгс/см2");fprintf($fp," %s","1.0510");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","НПИ температуры,гр.Целс");fprintf($fp,"%'.0s","");fprintf($fp," %s","-40.00");
        fprintf($fp,"%9s",'');fprintf($fp,"%s","ВПИ температуры,гр.Целс");fprintf($fp,"%s","60.00");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Верхний предел измерений расхода при рабоч.усл. (Qmax), м3/ч");fprintf($fp,"%'.0s","");fprintf($fp," %s","25.000");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Минимальный расход при рабочих условиях. (Qmin), м3/ч");fprintf($fp,"%'.7s","");fprintf($fp," %s","0.1000");fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s","Расход при котором счетчик останавливается, м3/ч");fprintf($fp,"%'.10s","");fprintf($fp," %s","0.03");fprintf($fp,"\n");

        fprintf($fp,"\n");
        fprintf($fp,"\n");
        fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%20s",'');fprintf($fp,"%s\n","ЧАСОВЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ");
        fprintf($fp,"%20s",'');fprintf($fp,"%'-70s \n",'');
        fprintf($fp,"%20s",'');
        fprintf($fp,"%3s",''); fprintf($fp,"%s",'Дата');
        fprintf($fp,"%5s",''); fprintf($fp,"%s",'Время');
        fprintf($fp,"%10s",''); fprintf($fp,"%s",'Объем , м3');
        fprintf($fp,"%5s",'');  fprintf($fp,"%s",'Ср.давл.');
        fprintf($fp,"%3s",''); fprintf($fp,"%s",'Cр.темп');
        fprintf($fp,"%3s",''); fprintf($fp,"%s",'АВ');fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%9s",''); fprintf($fp,"%s",'Начало');fprintf($fp,"%1s",'');fprintf($fp,"%s",'Конец');

        fprintf($fp,"%5s",'');fprintf($fp,"%s",'р.у');fprintf($fp,"%5s",'');fprintf($fp,"%s",'с.у');

        fprintf($fp,"%5s",''); fprintf($fp,"%s",'кгс/см2'); fprintf($fp,"%5s",''); fprintf($fp,"%s",'гр.Цельс'); fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%'-70s \n",'');

        for($i=1;$i<=24;$i++){
            fprintf($fp, "%20s", '');
            fprintf($fp, " %s %1s %1s %1s %s %1s %s %2s %s %2s %s %3s %s \n", '31.01.16', '09:00', '10:00', '', '1.0000', '', '1.0215', '', '1.0510', '', '18.77', '', 'A');

        }
        fprintf($fp,"%20s",'');fprintf($fp,"%'-70s \n",'');

        fprintf($fp,"%20s",'');fprintf($fp,"%8s",'');fprintf($fp,"%s",'Итого');fprintf($fp,"%11s",'');fprintf($fp,"%s",'xx.xxx');fprintf($fp,"%3s",'');fprintf($fp,"%s",'yy.yyy');fprintf($fp,"\n");
        fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%60s",'');fprintf($fp,"%s \n ","Лист 1 из 2");
        fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");fprintf($fp,"\n");

        fprintf($fp,"%19s",'');fprintf($fp,"%s","Корректор ПК-3к N 157:3-234");fprintf($fp,"%10s",'');fprintf($fp,"%s \n","Т/п 1: Бпивденный Мих44");
        fprintf($fp,"%19s",'');fprintf($fp,"%s","Cуточный отчет за 31 января 2016 года");
        fprintf($fp,"\n");
        fprintf($fp,"\n");

        fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.21s %s","Аварийный объем за сутки при р.у , м3","","0.0000");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.21s %s","Аварийный объем за сутки при с.у , м3","","0");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.23s %s","Полный объем за сутки при с.у , м3","","хх.ххх");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.0s %s","Длительность измерительных авар. ситуаций за сутки , ч:мин:с","","хх:хх:хх");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.0s %s","Длительность методических авар. ситуаций за сутки , ч:мин:с","","хх:хх:хх");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.6s %s","Длительность отключения питания за сутки , ч:мин:с","","00:00:00");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.5s %s","Длит. постан. на несанкц. константы за сутки , ч:мин:с","","00:00:00");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.5s %s","Длит. работы когда расход был < НПИ за сутки ,ч:мин:с","","00:00:00");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s %'.0s %s","Показание счетчика газа наконец отчетного периода (р.у.) , м3","","xxxx");fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%'-30s ",'');fprintf($fp,"%s",'Конец отчета');fprintf($fp," %'-30s",'');fprintf($fp,"\n");

        fprintf($fp,"%20s",'');fprintf($fp,"%s ",'Представитель  поставщика');fprintf($fp,"%20s",'');fprintf($fp,"%s",'Представитель потребителя');fprintf($fp,"\n");
        fprintf($fp,"%20s",'');fprintf($fp,"%s ",'_________________________');fprintf($fp,"%20s",'');fprintf($fp,"%s",'_________________________');fprintf($fp,"\n");







        fclose($fp);
    }


    public function actionGenerate()
    {

        $fTRG= new FlouTechReportGenerator();
        $fTRG->counter_id=0;


        $fTRG->dayHourReportGenerate(16,2,15);
    }

    function generateFloutechCommand($counter_id,$command,$comandVariables){
        $correctorInfo=CorrectorToCounter::find()->where(['counter_id'=>$counter_id])->one();
        $commandInfo=FloutechCommands::find()->where(["command"=>$command])->one();
        $zag="AA";
        $query=$commandInfo->command;

        if($correctorInfo->corrector_id<16){
            $zag.="0".dechex($correctorInfo->corrector_id);
        }else{$zag.=dechex($correctorInfo->corrector_id);}



        for ($i=1;$i<=$commandInfo->variables_count;$i++){

            foreach($commandInfo->variables as $variable) {

                if($variable->order == $i) {

                    $query .= $comandVariables[$i];

                    }

            }

        }
        if(strlen($zag.$query)/2+3<16) {
            $len = "0".dechex(strlen($zag.$query)/2+3);
        }
        else
        {
            $len = dechex(strlen($zag.$query)/2+3);
        }

        //$len=sprintf("%x",);

        return $zag.$len.$query;

    }

    public function monthGenerate($month,$year,$lastday=31,$modemId)
    {

        for($j=1;$j<=$lastday;$j++) {
            $monhex=$month;
            $nextDay=$j+1;

            if($nextDay>$lastday)
            {
                $nextDay=1;
               $month++;
            }

            if($nextDay<16)
            {
                $nextDayHex="0".dechex($nextDay);
            }else{
                $nextDayHex=dechex($nextDay);
            }


            $mon = hexdec($monhex);
            $mon+=64;
            if($mon>255){
                $mon=hexdec($month);
            }
            $monhex=dechex($mon);

            if($j<16)
            {
                $jhex="0".dechex($j);
            }else{
                $jhex=dechex($j);}

            if($month<16){
                $monhex1="0".dechex($month);
            }else{
                $monhex1=dechex($month);
            }

            $comConv=new CommandConveyor();
            $comConv->modem_id=$modemId;
            $comConv->command=strtoupper($this->generateFloutechCommand(1000, 28, [1 => "01", 2 => $monhex . $jhex . $year, 3 => $monhex1 . $jhex . $year]));
            $comConv->status="ACTIVE";
            $comConv->command_type=2;
            $comConv->save();

            for($h=9;$h<=33;$h=$h+7) {
                $d=$j;
                $hd=$h;


                if($hd>24)
                {
                    $hd=$h-24;
                    $d++;
                }

                if($d<16)
                {
                    $jhex="0".dechex($d);
                }else{
                    $jhex=dechex($d);}

                if($hd<16)
                {
                    $hhex="0".dechex($hd);
                }else{
                    $hhex=dechex($hd); }



                $mon = hexdec($monhex);
                $mon+=64;

                if($mon>255){
                    $mon=hexdec($month);
                }

                if($mon<16){
                    $monhex="0".dechex($mon);
                }else{
                    $monhex=dechex($mon);
                }



                $comConv=new CommandConveyor();
                $comConv->modem_id=$modemId;
                $comConv->command=strtoupper($this->generateFloutechCommand(1000, 25, [1 => "01", 2 => $monhex . $jhex . $year, 3 => $hhex, 4 => $monhex1 . $nextDayHex . $year, 5 => "08"]));
                $comConv->status="ACTIVE";
                $comConv->command_type=2;
                $comConv->save();

            }




        }
    }


    public function actionGetmonthtxt()
    {

        $counterId=Yii::$app->request->get('counterId');

        if(9>date("H")){
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24*2));
        }else{
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24));
        }




        $dateArr=explode(" ",$date);
        $dateDetail=explode("-",$dateArr[0]);

        $dt= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-01");
        $dt->add(new \DateInterval('PT9H'));
        $dn= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-01");
        $dn->add(new \DateInterval('P'. $dt->format('t').'DT9H'));

        $counter=\app\models\CorrectorToCounter::find()->where(['id'=>$counterId])->one();

        $dateTime=DateTime::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $name=Name::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticHard=StaticDataHard::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticSensor=StaticDataSensor::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticGeneral=StaticDataGeneral::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $momentData=MomentData::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();

        $fTRG= new FlouTechReportGenerator();
        $fTRG->counter_id=$counterId;
        $hourData=$fTRG->dayHourReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);

        $correctorData=CorrectorsData::findOne(["all_id"=>$counterId]);
        $dayData=$fTRG->monthReportGenerate($dateDetail[0],$dateDetail[1]);


        $intervention=Intervention::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        $emSit=EmergencySituation::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        $emArr=[];

        foreach($emSit as $oneEmSit){

            if(!array_key_exists($oneEmSit->params,$emArr)){
                $emArr[$oneEmSit->params]=[
                    'params'=>$oneEmSit->params,
                    'timestamp'=>$oneEmSit->timestamp,
                    'duration'=>$oneEmSit->time,
                    'vsc'=>$oneEmSit->vsc,
                    'vrc'=>$oneEmSit->vrc,
                    'countP'=>$oneEmSit->count_p,
                    'var1'=>$oneEmSit->var1,
                ];
            }else{

                $emArr[$oneEmSit->params]['duration']+=$oneEmSit->time;
                $emArr[$oneEmSit->params]['vsc']+=$oneEmSit->vsc;
                $emArr[$oneEmSit->params]['vrc']+=$oneEmSit->vrc;
                $emArr[$oneEmSit->params]['countP']+=$oneEmSit->count_p;
                $emArr[$oneEmSit->params]['var1']+=$oneEmSit->var1;


            }



        }

        $emSit=$emArr;

        $emSign=EmergencySign::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        $diag=Diagnostic::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();



            if($correctorData->measured_value=="Quantity") {
                $content = $this->renderPartial("otchetm", [
                    'counter'=>$counter,
                    'Ksgim'=>$momentData->k,
                    'datetext' => $this->getMonth($dayData[0]->month) . ' 20' . $dayData[0]->year . " года ",
                    'programName' => "ASER ".Yii::$app->params['version'],
                    'time' => $dateTime->time,
                    'date' => $dateTime->date,
                    'correctorName' => $name->corrector_name,
                    'serialNumber' => $staticGeneral->zavod_number,
                    'complexName' => $staticGeneral->complex_name,
                    'tubeName' => $name->tube_name,
                    'poVersion' => $name->version,
                    'metodName' => 'NX19 мод',
                    'counterName' => $staticSensor->counter_name,
                    'metodDimName' => "Счетчик",
                    'contractTime' => "9:00",
                    'density' => round($staticHard->density, 3),
                    'molCO2' => round($staticHard->mol_co2, 2),
                    'pressureType' => "Абсолютное",
                    'molN2' => round($staticHard->mol_n2, 2),
                    'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                    'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                    'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                    'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                    'impulseOnM3' => "10.00",
                    'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                    'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                    'qStop' => round($staticSensor->qstop, 4),
                    'hours' => $hourData,
                    'intervention'=>$intervention,
                    'emsig'=>$emSign,
                    'emsit'=>$emSit,
                    'diag'=>$diag,
                    'daydata'=>$dayData,
                    /*
                    'sumRC' => round($dayData->v_rc, 2),
                    'sumSC' => round($dayData->v_sc, 2),
                    'bezavV' => round($dayData->v_rc, 2),*/
                    'avV' => 0.0,
                  /* 'polV' => round($dayData->v_rc, 2),
                    'bezavVsu' => round($dayData->v_sc, 2),
                    'polVsu' => round($dayData->v_sc, 1),*/
                    'avVsu' => 0.0,
                   // 'polVsu' => round($dayData->v_sc, 1),
                     'timeQem' => 0,
                    'pokaz' => round($dayData[count($dayData)-1]->vpokaz_rc, 2),
                    'timeEmrg' => 0,


                ]);
            }
            else{

                $content = $this->renderPartial("otchetpm", [
                    'counter'=>$counter,
                    'Ksgim'=>$momentData->k,
                    'datetext' => $this->getMonth($dayData[0]->month) . ' 20' . $dayData[0]->year . " года ",
                    'programName' =>"ASER ". Yii::$app->params['version'],
                    'time' => $dateTime->time,
                    'date' => $dateTime->date,
                    'correctorName' => $name->corrector_name,
                    'serialNumber' => $staticGeneral->zavod_number,
                    'complexName' => $staticGeneral->complex_name,
                    'tubeName' => $name->tube_name,
                    'poVersion' => $name->version,
                    'metodName' => 'ГОСТ8.586-NX19 мод',
                    /// 'counterName' => $staticSensor->counter_name,
                    'metodDimName' => "Перепад на СУ",
                    'contractTime' => "9:00",
                    'density' => round($staticHard->density, 3),
                    'molCO2' => round($staticHard->mol_co2, 2),
                    'pressureType' => "Абсолютное",
                    'molN2' => round($staticHard->mol_n2, 2),
                    'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                    'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                    'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                    'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                    'impulseOnM3' => "10.00",
                    'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                    'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                    'qStop' => round($staticSensor->qstop, 4),
                    'hours' => $hourData,
                    'intervention'=>$intervention,
                    'emsig'=>$emSign,
                    'emsit'=>$emSit,
                    'diag'=>$diag,
                    'daydata'=>$dayData,
                    'typeOtb'=>"Угловой",
                    'Dtube'=>round($staticHard->d_tube,3),
                    'Dsu'=>round($staticHard->d_sug_device,3),
                    'otsechka'=>round($staticHard->otsechka,4),
                    'sharpness'=>round($staticHard->sharpness,5),
                    'a0'=>"10.7",
                    'a0su'=>"15.5",
                    'a1'=>"12.0",
                    'a1su'=>"10.5",
                    'a2'=>"0.0",
                    'a2su'=>"0.0",
                    'Dvyaz'=>"1.06275e-06",
                    'perepNPI' => round(10.000, 3),
                    'perepVPI' => round(4001.0, 1),
                    "mki"=>round($staticHard->control_interval,0),
                    "Rzkrug"=>round($staticHard->radius_diafr, 2),
                   /* 'sumRC' => round($dayData->v_rc, 2),
                    'sumSC' => round($dayData->v_sc, 2),
                    'bezavV' => round($dayData->v_rc, 2),*/
                    'avV' => 0.0,
                  /*  'polV' => round($dayData->v_rc, 2),
                    'bezavVsu' => round($dayData->v_sc, 2),
                    'polVsu' => round($dayData->v_sc, 1),*/
                    'avVsu' => 0.0,
                   // 'polVsu' => round($dayData->v_sc, 1),
                    'timeQem' => 0,
                    'pokaz' => round($dayData[count($dayData)-1]->vpokaz_rc, 2),
                    'timeEmrg' => 0,


                ]);
            }



        //echo $result;

        //$content = $this->renderPartial('_reportView');

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename'=>$counter->address->fulladdress."-".$dt->format('Y-m').'.pdf',

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => [ 'SetFooter'=>["Лист {PAGENO} из 2","RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline=0;


        return $pdf->render();

    }



    public function actionGettxt()
    {

        $counterId=Yii::$app->request->get('counterId');

        if(9>date("H")){
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24*2));
        }else{
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24));
        }

        $dateArr=explode(" ",$date);
        $dateDetail=explode("-",$dateArr[0]);

        $dt= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-".$dateDetail[2]);
        $dt->add(new \DateInterval('PT9H'));
        $dn= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-".$dateDetail[2]);
        $dn->add(new \DateInterval('P1DT9H'));





        $counter=\app\models\CorrectorToCounter::find()->where(['id'=>$counterId])->one();

        $dateTime=DateTime::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $name=Name::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticHard=StaticDataHard::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticSensor=StaticDataSensor::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $staticGeneral=StaticDataGeneral::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        $momentData=MomentData::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
        //$hourDataA=HourData::find()->where(['all_id'=>$counterId])->limit(24)->orderBy(['created_at'=>SORT_DESC,'day'=>SORT_ASC,'hour_n'=>SORT_ASC])->asArray()->all();
        // $hourData=HourData::find()->where(['all_id'=>$counterId])->limit(24)->orderBy(['id'=>SORT_DESC])->all();
        /* $hourData=HourData::find()->where('hour_n >=:hour_n AND all_id=:all_id AND month=:month AND year=:year AND day=:day',[":hour_n"=>9,':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0],':day'=>$dateDetail[2]])
             ->orWhere('hour_n <:hour_n1 AND all_id=:all_id1 AND month=:month1 AND year=:year1 AND day=:day1',[":hour_n1"=>9,':all_id1'=>$counterId,':month1'=>$dateDetail[1],':year1'=>$dateDetail[0],':day1'=>$dateDetail[2]+1])->limit(24)->orderBy(['id'=>SORT_DESC])->all();
 */
        $fTRG= new FlouTechReportGenerator();
        $fTRG->counter_id=$counterId;
        $hourData=$fTRG->dayHourReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);

        $correctorData=CorrectorsData::findOne(["all_id"=>$counterId]);


        // $dayData=DayData::find()->where(['all_id'=>$counterId,'month'=>$dateDetail[1],'year'=>$dateDetail[0],'day'=>$dateDetail[2]])->orderBy(['id'=>SORT_DESC])->one();

        $dayData=$fTRG->dayDayReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);
        //$hourData=array_reverse($hourData);

        $intervention=Intervention::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        $emSit=EmergencySituation::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();

        $emArr=[];

        foreach($emSit as $oneEmSit){

            if(!array_key_exists($oneEmSit->params,$emArr)){
                $emArr[$oneEmSit->params]=[
                    'params'=>$oneEmSit->params,
                    'timestamp'=>$oneEmSit->timestamp,
                    'duration'=>$oneEmSit->time,
                    'vsc'=>$oneEmSit->vsc,
                    'vrc'=>$oneEmSit->vrc,
                    'countP'=>$oneEmSit->count_p,
                    'var1'=>$oneEmSit->var1,
                ];
            }else{

                $emArr[$oneEmSit->params]['duration']+=$oneEmSit->time;
                $emArr[$oneEmSit->params]['vsc']+=$oneEmSit->vsc;
                $emArr[$oneEmSit->params]['vrc']+=$oneEmSit->vrc;
                $emArr[$oneEmSit->params]['countP']+=$oneEmSit->count_p;
                $emArr[$oneEmSit->params]['var1']+=$oneEmSit->var1;


            }



        }

        $emSit=$emArr;



        $emSign=EmergencySign::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        $diag=Diagnostic::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


        if(count($hourData)>=24){

            if($correctorData->measured_value=="Quantity") {
                $content = $this->renderPartial("otchet", [
                    'Ksgim'=>$momentData->k,
                    'counter'=>$counter,
                    'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                    'programName' => "ASER ".Yii::$app->params['version'],
                    'time' => $dateTime->time,
                    'date' => $dateTime->date,
                    'correctorName' => $name->corrector_name,
                    'serialNumber' => $staticGeneral->zavod_number,
                    'complexName' => $staticGeneral->complex_name,
                    'tubeName' => $name->tube_name,
                    'poVersion' => $name->version,
                    'metodName' => 'NX19 мод',
                    'counterName' => $staticSensor->counter_name,
                    'metodDimName' => "Счетчик",
                    'contractTime' => "9:00",
                    'density' => round($staticHard->density, 3),
                    'molCO2' => round($staticHard->mol_co2, 2),
                    'pressureType' => "Абсолютное",
                    'molN2' => round($staticHard->mol_n2, 2),
                    'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                    'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                    'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                    'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                    'impulseOnM3' => "10.00",
                    'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                    'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                    'qStop' => round($staticSensor->qstop, 4),
                    'hours' => $hourData,
                    'intervention'=>$intervention,
                    'emsig'=>$emSign,
                    'emsit'=>$emSit,
                    'diag'=>$diag,

                    'sumRC' => round($dayData->v_rc, 2),
                    'sumSC' => round($dayData->v_sc, 2),
                    'bezavV' => round($dayData->v_rc, 2),
                    'avV' => round($dayData->vav_rc, 1),
                    'polV' => round($dayData->v_rc, 2),
                    'bezavVsu' => round($dayData->v_sc, 2),
                    'polVsu' => round($dayData->v_sc, 1),
                    'avVsu' => $dayData->vav_sc,
                    'polVsu' => round($dayData->v_sc+$dayData->vav_sc, 1),
                    'timeQem' =>  $dayData->time_emerg2,
                    'pokaz' => round($dayData->vpokaz_rc, 2),
                    'timeEmrg' =>$dayData->time_emerg,


                ]);
            }
            else{

                $content = $this->renderPartial("otchetp", [
                    'counter'=>$counter,
                    'Ksgim'=>$momentData->k,
                    'datetext' => $hourData[0]->day . ' ' . $this->getMonth($hourData[0]->month) . ' 20' . $hourData[0]->year . " года ",
                    'programName' =>"ASER ". Yii::$app->params['version'],
                    'time' => $dateTime->time,
                    'date' => $dateTime->date,
                    'correctorName' => $name->corrector_name,
                    'serialNumber' => $staticGeneral->zavod_number,
                    'complexName' => $staticGeneral->complex_name,
                    'tubeName' => $name->tube_name,
                    'poVersion' => $name->version,
                    'metodName' => 'ГОСТ8.586-NX19 мод',
                    /// 'counterName' => $staticSensor->counter_name,
                    'metodDimName' => "Перепад на СУ",
                    'contractTime' => "9:00",
                    'density' => round($staticHard->density, 3),
                    'molCO2' => round($staticHard->mol_co2, 2),
                    'pressureType' => "Абсолютное",
                    'molN2' => round($staticHard->mol_n2, 2),
                    'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                    'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                    'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                    'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                    'impulseOnM3' => "10.00",
                    'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                    'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                    'qStop' => round($staticSensor->qstop, 4),
                    'hours' => $hourData,
                    'intervention'=>$intervention,
                    'emsig'=>$emSign,
                    'emsit'=>$emSit,
                    'diag'=>$diag,
                    'typeOtb'=>"Угловой",
                    'Dtube'=>round($staticHard->d_tube,3),
                    'Dsu'=>round($staticHard->d_sug_device,3),
                    'otsechka'=>round($staticHard->otsechka,4),
                    'sharpness'=>round($staticHard->sharpness,5),
                    'a0'=>"10.7",
                    'a0su'=>"15.5",
                    'a1'=>"12.0",
                    'a1su'=>"10.5",
                    'a2'=>"0.0",
                    'a2su'=>"0.0",
                    'Dvyaz'=>"1.06275e-06",
                    'perepNPI' => round(10.000, 3),
                    'perepVPI' => round(4001.0, 1),
                    "mki"=>round($staticHard->control_interval,0),
                    "Rzkrug"=>round($staticHard->radius_diafr, 2),
                    'sumRC' => round($dayData->v_rc, 2),
                    'sumSC' => round($dayData->v_sc, 2),
                    'bezavV' => round($dayData->v_rc, 2),
                    'avV' => round($dayData->vav_rc, 1),
                    'polV' => round($dayData->v_rc, 2),
                    'bezavVsu' => round($dayData->v_sc, 2),
                    'polVsu' => round($dayData->v_sc+$dayData->vav_sc, 1),
                    'avVsu' => round($dayData->vav_sc, 1),
                    'polVsu' => round($dayData->v_sc, 1),
                    'timeQem' => $dayData->time_emerg2,
                    'pokaz' => round($dayData->vpokaz_rc, 2),
                    'timeEmrg' => $dayData->time_emerg,


                ]);
            }}else
        {
            $content="Отчет за ".$date." будет сформирован после 9:00";
        }


        //echo $result;

        //$content = $this->renderPartial('_reportView');

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename'=>$counter->address->fulladdress."-".$dt->format('Y-m-d').'.pdf',
           // 'defaultFont'=>,

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => [ 'SetFooter'=>["Лист {PAGENO} из 2","RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline=0;


        return $pdf->render();

    }



    public function actionNew()
    {


        $this->layout='smartAdminN';
        $address = new Counter();
        $address->CounterList();

        return $this->render('new',[
            'dataProvider' => $address->getDataProvider(),
            'searchModel'=>$address->getSearchModel()

        ]);


    }

     public function actionAutocomplete()
    {
        Yii::$app->response->format = 'json';
        $text=Yii::$app->request->get("term",false);
        $search=Search::find()->where(['like','search_string',$text])->asArray()->all();


        return $search;

    }


    public function actionNew2()
    {


        $this->layout='smartAdminN';

        $address = new Counter();
        $address->EditCounter();

        return $this->render('new2',[
            'counter' => $address->getModel()
        ]);


    }


    public function actionGetem()
    {

        $this->layout='onlyGrid';

        $counterId=Yii::$app->request->get('counterId');

        if(9>date("H")){
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24*2));
        }else{
            $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24));
        }

        $dateArr=explode(" ",$date);
        $dateDetail=explode("-",$dateArr[0]);

        $dt= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-".$dateDetail[2]);
        $dt->add(new \DateInterval('PT9H'));
        $dn= new \DateTime($dateDetail[0]."-".$dateDetail[1]."-".$dateDetail[2]);
        $dn->add(new \DateInterval('P29DT9H'));




        $emSit=EmergencySituation::find()
            ->where(['all_id'=>$counterId])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['timestamp'=>SORT_ASC])->all();


        $emArr=[];

        foreach($emSit as $oneEmSit){

            if(!array_key_exists($oneEmSit->params,$emArr)){
                $emArr[$oneEmSit->params]=[
                    'params'=>$oneEmSit->params,
                    'timestamp'=>$oneEmSit->timestamp,
                    'duration'=>$oneEmSit->time,
                    'vsc'=>$oneEmSit->vsc,
                    'vrc'=>$oneEmSit->vrc,
                    'countP'=>$oneEmSit->count_p,
                    'var1'=>$oneEmSit->var1,
                ];
            }else{

                $emArr[$oneEmSit->params]['duration']+=$oneEmSit->time;
                $emArr[$oneEmSit->params]['vsc']+=$oneEmSit->vsc;
                $emArr[$oneEmSit->params]['vrc']+=$oneEmSit->vrc;
                $emArr[$oneEmSit->params]['countP']+=$oneEmSit->count_p;
                $emArr[$oneEmSit->params]['var1']+=$oneEmSit->var1;


            }



        }



        return $this->render("emergency", [
            'emsit'=>$emArr,
            'avVsu' => 0,
            'avV' => 0,

        ]);



    }



}
