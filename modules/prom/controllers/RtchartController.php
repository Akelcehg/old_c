<?php

namespace app\modules\prom\controllers;


use app\components\ChartCalc;
use app\components\Events\Events;
use app\components\FlouTechCommandGenerator;
use app\models\CommandConveyor;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use app\models\DayData;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\HourData;
use app\models\Intervention;
use app\models\MomentData;
use app\models\Option;
use app\models\SimCard;
use app\modules\prom\components\DiagnosticWidget;
use app\modules\prom\components\EmergencySituationWidget;
use app\modules\prom\components\ForcedPaymentButton;
use app\modules\prom\components\ForcedReportButton;
use app\modules\prom\components\InterventionWidget;
use app\modules\prom\components\Limit\Limits;
use app\modules\prom\components\RtSwitchWidget;
use Faker\Provider\DateTime;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use Yii;

class RtchartController extends Controller
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
                                'rtswitch',
                                'rtview',
                                'ajaxcheck',
                                'ajaxhourdata',
                                'ajaxdaydata',
                                'ajaxdaydataall',
                                'ajaxmonth',
                                'ajaxhourdatachart',
                                'ajaxweekdata',
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['admin', 'PromAdmin'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }

    public function actionIndex() {

        $globalChartSettings = [
            'responsive' =>'false',
            'animation' => 'false',
            'showTooltips'=>'false',
            'tooltipTemplate' => '"<%= value %> куб."'];


        echo $this->render('index');
    }



    public function AjaxSetCommand()
    {   $rtEnabled=Option::find()->where(['name'=>'rtchart'])->one();
        if(!empty($rtEnabled) and $rtEnabled->value==1) {


            $dt=new \DateTime($rtEnabled->label);
            $dn=new \DateTime();
            $dn->sub(new \DateInterval("PT1H"));
            if($dn>=$dt){
                $rtEnabled->value=0;
                $rtEnabled->save();
            }


            $correctors = CorrectorToCounter::find();
            $corCount = clone $correctors;
            $corCount = $corCount->count();
            foreach ($correctors->all() as $corrector) {

                if (!$corrector->isForcedMomentData()) {
                    $rtArr=Option::find()->where(['name'=>'rtarr'])->one();

                    $arr=json_decode($rtArr->value);

                    print_r($arr);
                    if(!is_array($arr)){
                        $arr=[];
                    }
                    $buf=false;
                   foreach($arr as $ar){
                       if($ar == $corrector->id){
                           $buf=true;
                       }




                    }

                    if(!$buf){
                        $arr =array_merge($arr,[$corrector->id=>$corrector->id]) ;
                    }
                    $rtArr->label='utuut';
                    $rtArr->value =json_encode($arr);
                    $rtArr->save();


                    $flouTechComGen = new FlouTechCommandGenerator();
                    $flouTechComGen->counter_id = $corrector->id;
                    $flouTechComGen->MomentDataCommand([1 => $corrector->branch_id]);
                }

            }

            return true;
        }else{
            $rtArr=Option::find()->where(['name'=>'rtarr'])->one();
            $rtArr->label='uyuyut';
            $rtArr->value='[]';
            $rtArr->save();
            print_r($rtArr->getErrors());
        }
    }

    public function actionRtview()
    {

        return RtSwitchWidget::widget();

    }

    public function actionRtswitch()
    {
        $rtEnabled=Option::find()->where(['name'=>'rtchart'])->one();
        if(!empty($rtEnabled)) {
            if ($rtEnabled->value == '1') {
                $rtEnabled->value = '0';
            } else {
                $rtEnabled->value = '1';
                $rtEnabled->label=date("Y-m-d H:i:s");
            }
            $rtEnabled->save();
        }else{
            $rtEnabled=new Option();
            $rtEnabled->label=date("Y-m-d H:i:s");
            $rtEnabled->name='rtchart';
            $rtEnabled->value='1';
            $rtEnabled->input_type='checkbox';
            $rtEnabled->save();

            $rtEnabled=new Option();
            $rtEnabled->label='';
            $rtEnabled->name='rtarr';
            $rtEnabled->value='0';
            $rtEnabled->input_type='checkbox';
            $rtEnabled->save();
        }

        $events = new Events();
        $events->oldAttributes =  $rtEnabled->getOldAttributes();
        $events->newAttributes =  $rtEnabled->getAttributes();
        $events->model =  $rtEnabled;
        $events->type = 'edit';
        $events->AddEvent();

        return RtSwitchWidget::widget();
    }

    public function GetCorrectorsMomentData($type="prom"){
        $correctors=CorrectorToCounter::find()
            ->filterWhere(['type'=>$type])
            ->all();
        $dayConsumption=0;

        $ds=new \DateTime();

        $dt=new \DateTime(date("Y-m-d"));
        $dn=new \DateTime();
        //$dt->sub(new \DateInterval('P10D'));
        $contractHour=9;

        if($contractHour and ($dn->format("H")<$contractHour)){
            $dt->sub(new \DateInterval('P1D'));
            $dt->add(new \DateInterval('PT'.$contractHour.'H'));
            $date = $dt->format("Y-m-d H:i:s");
            $ds->sub(new \DateInterval('PT'. $ds->format("i").'M'.$ds->format("s").'S'));
            $datec = $ds->format("Y-m-d H:i:s");
         ;
        }else{

            $dt->add(new \DateInterval('PT'.$contractHour.'H'));
            $date = $dt->format("Y-m-d H:i:s");
            $ds->sub(new \DateInterval('PT'. $ds->format("i").'M'.$ds->format("s").'S'));
            $datec = $ds->format("Y-m-d H:i:s");

        }

        foreach($correctors as $corrector){


           /* $momentData=MomentData::find()
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>','created_at',$date])
                ->orderBy(['id'=>SORT_DESC])
                ->one();*/

            $query = (new Query())
                ->select("*")
                ->from("MomentData")
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>','created_at',$date])
                ->orderBy(['id'=>SORT_DESC]);
            $momentData=$query->createCommand(Yii::$app->db)->queryOne();

            if(!empty($momentData)) {
                $dayConsumption += $momentData['vday_sc'];
            }

        }
        return $dayConsumption;
    }


    public function actionChartData()
    {
        $cons=$this->GetCorrectorsMomentData();
        $time=date("i:s");

        $result=[
            'label'=>$time,
            'data'=>[round($cons,2)],
        ];
        Yii::$app->response->format = 'json';
        return $result;

    }

    public function actionAjaxcheck()
    {   $this->AjaxSetCommand();
        $type=YII::$app->request->get('type',"prom");
            $cons=$this->GetCorrectorsMomentData($type);

        $dt=new \DateTime();

        if($dt->format("H")<9){
            $dt->sub(new \DateInterval("P1D"));
        }

        $result=[
            'label'=>$dt->format("j"),
            'data'=>[round($cons,2)],
        ];
        Yii::$app->response->format = 'json';
        return $result;

    }


    public function GetCorrectorsHourData($date=false,$id=null){
        $correctors=CorrectorToCounter::find()
            ->filterWhere(['id'=>$id])
            ->andFilterWhere(['device_type'=>'floutech'])
            ->andFilterWhere(['work_status'=>'work'])
            ->all();

        $dayConsumption=[];
        if($date){
            $date =new \DateTime($date);
            $dt=new \DateTime($date->format("Y-m-d"));
        }else{
            $dt=new \DateTime(date("Y-m-d"));
        }

        $dn=clone $dt;


        foreach($correctors as $corrector){
        //$dt->sub(new \DateInterval('P10D'));
        $do=DateOptions::find()->where(['all_id'=>$corrector->id])->orderBy(['id'=>SORT_DESC])->one();
        $contractHour=$do->contract_hour;



        if($contractHour and ($dn->format("H")<$contractHour)){
           // $dt->sub(new \DateInterval('P1D'));
            $dt->add(new \DateInterval('PT'.$contractHour.'H'));
            $date = $dt->format("Y-m-d H:i:s");
            $dt->add(new \DateInterval('P1D'));
            $datec = $dt->format("Y-m-d H:i:s");
        }else{
            $dt->add(new \DateInterval('PT'.$contractHour.'H'));
            $date = $dt->format("Y-m-d H:i:s");
            $dt->add(new \DateInterval('P1D'));
            $datec = $dt->format("Y-m-d H:i:s");
        }






           /*$hoursData=HourData::find()
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$date])
                ->andWhere(['<','timestamp',$datec])
                ->orderBy(['timestamp'=>SORT_ASC])
                ->all();*/

            $query = (new Query())
                ->select("*")
                ->from("HourData")
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$date])
                ->andWhere(['<','timestamp',$datec])
                ->orderBy(['timestamp'=>SORT_ASC]);
             $hoursData=$query->createCommand(Yii::$app->db)->queryAll();


            foreach($hoursData as $hourData){

                if(isset($dayConsumption[$hourData['timestamp']])){
                    $dayConsumption[$hourData['timestamp']]+=$hourData['v_sc'];
                }else{
                    $dayConsumption[$hourData['timestamp']]=$hourData['v_sc'];
                }

            }
            $dt=clone $dn;
        }



        return $dayConsumption;
    }

    public function GetCorrectorsDayData($date=false,$id=null,$duration=false,$type="prom")
    {
        $correctors = CorrectorToCounter::find()
            ->filterWhere(['id' => $id])
            ->andFilterWhere(['type'=>$type])
            ->andWhere(['work_status'=>'work'])
            ->andFilterWhere(['device_type'=>'floutech'])
            ->all();
        $dayConsumption = [];


        foreach($correctors as $corrector){

            $do=DateOptions::find()->where(['all_id'=>$corrector->id])->orderBy(['id'=>SORT_DESC])->one();

        if ($date) {


            $date2 =new \DateTime($date);


            if($duration){
                $dt=new \DateTime($date2->format("Y-m-d"));
                $dt->add(new \DateInterval('P1DT'.$do->contract_hour.'H'));
                $dk=clone $dt;
                $dt->sub(new \DateInterval('P'.$duration.'D'));
            }else{
                $dt=new \DateTime($date2->format("Y-m-t"));
                $dt->add(new \DateInterval('P1DT'.$do->contract_hour.'H'));
                $dk=clone $dt;
                $dt->sub(new \DateInterval('P'.($dt->format('t')-1).'D'));
            }

        } else {

        $dt = new \DateTime(date("Y-m-d"));
            $dt->add(new \DateInterval('P1DT'.$do->contract_hour.'H'));
            $dk= new \DateTime();
        $dt->sub(new \DateInterval('P30D'));
    }




            $hoursData=DayData::find()
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$dt->format("Y-m-d H:i:s")])
                ->orderBy(['timestamp'=>SORT_ASC])
                ->all();

            $query = (new Query())
                ->select("*")
                ->from("DayData")
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$dt->format("Y-m-d H:i:s")])
                ->andWhere(['<','timestamp',$dk->format("Y-m-d H:i:s")])
                ->orderBy(['timestamp'=>SORT_ASC]);

            $hoursData=$query->createCommand(Yii::$app->db)->queryAll();

            foreach($hoursData as $hourData){
                $dz=new \DateTime($hourData['timestamp']);
                $date3=$dz->format("Y-m-d");
                if(isset($dayConsumption[$date3])){
                    $dayConsumption[$date3]+=$hourData['v_sc'];
                }else{
                    $dayConsumption[$date3]=$hourData['v_sc'];
                }

            }

        }


        return $dayConsumption;
    }
    public function GetCorrectorsDayData2($date=false,$id=null,$duration=false,$type="prom")
    {
        $correctors = CorrectorToCounter::find()
            ->filterWhere(['id' => $id])
            ->andFilterWhere(['type'=>$type])
            ->all();
        $dayConsumption = [];
        if ($date) {
            $date =new \DateTime($date);

            if($duration){
                $dt=new \DateTime($date->format("Y-m-d"));
                $dt->sub(new \DateInterval('P'.$duration.'D'));
            }else{
                $dt=new \DateTime($date->format("Y-m-t"));
                $dt->sub(new \DateInterval('P'.$dt->format('t').'D'));
            }

        } else {

            $dt = new \DateTime(date("Y-m-d"));
            $dt->sub(new \DateInterval('P30D'));
        }
        foreach($correctors as $corrector){

            $hoursData=DayData::find()
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$dt->format("Y-m-d H:i:s")])
                ->orderBy(['timestamp'=>SORT_ASC])
                ->all();

            $query = (new Query())
                ->select("*")
                ->from("DayData")
                ->where(['all_id'=>$corrector->id])
                ->andWhere(['>=','timestamp',$dt->format("Y-m-d H:i:s")])
                ->orderBy(['timestamp'=>SORT_ASC]);

            $hoursData=$query->createCommand(Yii::$app->db)->queryAll();

            foreach($hoursData as $hourData){
                $dn=new \DateTime($hourData['timestamp']);
                $date=$dn->format("j");
                if(isset($dayConsumption[$date])){
                    $dayConsumption[$date]+=$hourData['v_sc'];
                }else{
                    $dayConsumption[$date]=$hourData['v_sc'];
                }

            }
        }


        return $dayConsumption;
    }


    public function ajaxhourdatachart( $beginDate, $id)
    {
        $cons=$this->GetCorrectorsHourData($beginDate,$id);
        $result=[];

        foreach($cons as $key=>$value){
            $dt=new \DateTime($key);
            $dn = clone $dt;
            $dn->add(new \DateInterval('PT1H'));
            $result[]=[
                'label'=> $dt->format("H").'-'.$dn->format("H"),
                'data'=>[round($value,2)],
            ];
        }


        return $result;

    }



    public function actionAjaxhourdatachart()
    {
        $beginDate =Yii::$app->request->get('beginDate',false);
        $id =Yii::$app->request->get('counter_id',null);

        $cons=$this->GetCorrectorsHourData($beginDate,$id);
        $result=[];

        foreach($cons as $key=>$value){
            $dt=new \DateTime($key);
            $dn = clone $dt;
            $dn->add(new \DateInterval('PT1H'));
            $result[]=[
                'label'=> $dt->format("H").'-'.$dn->format("H"),
                'data'=>[round($value,2)],
            ];
        }

        Yii::$app->response->format = 'json';
        return $result;

    }

    public function actionAjaxhourdata()
    {
        $cons=$this->GetCorrectorsHourData();
        $result=[];

        foreach($cons as $key=>$value){
            $dt=new \DateTime($key);
            $dn = clone $dt;
            $dn->add(new \DateInterval('PT1H'));
            $result[]=[
                'label'=> $dt->format("H").'-'.$dn->format("H"),
                'data'=>[round($value,2)],
            ];
        }

        Yii::$app->response->format = 'json';
        return $result;

    }

    public function actionAjaxweekdata()
    {

        $id =Yii::$app->request->post('counter_id',null);
        $data =Yii::$app->request->post('data',false);

        $label = [];
        $count = 0;
        $ij = 0;
        for ($i = 1; $i <= 7; $i++) {
            $label[] = $this->ajaxhourdatachart($data[$i - 1],$id);
            if ($count < count($label[$i - 1])) {
                $count = count($label[$i - 1]);
                $ij = $i - 1;
            }
        }

        $output = [];

        for ($i = 0; $i < $count; $i++) {
            $data = [];
            for ($j = 0; $j < 7; $j++) {
                if (isset($label[$j][$i]['data'][0])) {

                    $data[] = $label[$j][$i]['data'][0];
                } else {
                    $data[] = 0;
                }
            }

            $output[] = ['data' => $data, 'label' => $label[$ij][$i]['label']];
        }
        Yii::$app->response->format = 'json';
        return $output;
    }

    public function actionAjaxdaydata()
    {

        $beginDate =Yii::$app->request->get('beginDate',false);
        $type=Yii::$app->request->get('type',"prom");
        $id =Yii::$app->request->get('counter_id',null);
        $data =Yii::$app->request->get('data',false);

        if(!is_null($id)){
            $type=null;
        }

        if($data){
            $beginDate=$data[count($data-1)];
            $duration=7;
        }else{
            $duration=false;
        }

        $cons=$this->GetCorrectorsDayData($beginDate,$id,$duration,$type);
        $result=[];

        $limit = new Limits();



        foreach($cons as $key=>$value){

            $dt=new \DateTime($key);

            $limit->all_id=$id;
            $limit->year=$dt->format('Y');
            $limit->month=$dt->format('m');


            if ($limit->GetLimit() ) {

                $limits=$limit->GetLimit()->limit/$dt->format('t');


            } else {

                $limits=0;

            }

            if(!is_null($id)){   $result[]=[
                'label'=> sprintf('%d',$dt->format("d"),Yii::$app->formatter->asDate($dt,'LLL')),
                'month'=>  $dt->format("m"),
                'limit'=>[round($limits,2)],
                'data'=>[round($value,2),round($limits,2)],
            ];}else{
                $result[]=[
                    'label'=> sprintf('%d',$dt->format("d"),Yii::$app->formatter->asDate($dt,'LLL')),
                    'month'=>  $dt->format("m"),
                    'limit'=>[round($limits,2)],
                    'data'=>[round($value,2)],
                ];
            }

        }

        Yii::$app->response->format = 'json';
        return $result;

    }

    public function actionAjaxmonth()
    {

        $cons=$this->GetCorrectorsDayData();
        $result=[];
        $result2=[];
        foreach($cons as $key=>$value){

            $dt=new \DateTime($key);

            if(isset($result[$dt->format("Y-m")])){
                $result[$dt->format("Y-m")]++;
            }else{
                $result[$dt->format("Y-m")]=1;
            }

        }


        foreach($result as $key=>$value){

            $dt=new \DateTime($key);

                $result2[]=[
                    'label'=>$key,
                    'width'=>$value/count($cons)
                ];

        }


        Yii::$app->response->format = 'json';
        return $result2;

    }

    public function actionAjaxdaydataall()
    {

        $beginDate =Yii::$app->request->get('beginDate',false);
        $type=Yii::$app->request->get('type',"prom");
        $id =Yii::$app->request->get('counter_id',null);
        $data =Yii::$app->request->get('data',false);

        $dk=new \DateTime();


        $calc=new ChartCalc();
        $calc->endDate=$dk->format("Y-m-d");
        $dk->sub(new \DateInterval("P30D"));
        $calc->beginDate=$dk->format("Y-m-d");

        $arr=$calc->graph();

        if($data){
            $beginDate=$data[count($data-1)];
            $duration=7;
        }else{
            $duration=false;
        }

        $cons=$this->GetCorrectorsDayData2($beginDate,$id,$duration,$type);
        $result=[];

       /* foreach($cons as $key=>$value){

            $dt=new \DateTime($key);
            $result[]=[
                'label'=> sprintf('%d',$dt->format("d"),Yii::$app->formatter->asDate($dt,'LLL')),
                'month'=>  $dt->format("m"),
                'data'=>[round($value,2)],
            ];
        }*/

        foreach($arr as $arro){

            if(isset($cons[$arro['label']])){
                $add=$cons[$arro['label']];
            }else{
                $add=0;
            }


            $result[]=[
                'label'=> $arro['label'],

                'data'=>[round($arro['data'][0]+$add,2)],
            ];
        }

        Yii::$app->response->format = 'json';
        return $result;

    }


}