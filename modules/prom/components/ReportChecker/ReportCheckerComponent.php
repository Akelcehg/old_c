<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:03
 */

namespace app\modules\prom\components\ReportChecker;


use app\components\FlouTechReportGenerator;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use app\models\DateTime;
use app\models\DayData;
use app\models\HourData;
use app\models\MomentData;
use app\models\Name;
use app\models\PromReport;
use app\models\StaticDataGeneral;
use app\models\StaticDataHard;
use app\models\StaticDataSensor;
use Yii;
use yii\base\Component;

/**
 * This is the model class for table "corrector_to_counter".
 *
 * @property integer $id
 * @property \DateTime $date
 * @property [] $errors
 * @property CorrectorToCounter $corrector
 */
class ReportCheckerComponent extends Component
{
    public $id = false;
    public $date = false;
    public $errors=[];
    private $corrector = false;
    private $contractHour=9;


    private function getCorrector()
    {

        if ($this->id) {
            $this->corrector = CorrectorToCounter::find()->where(['id' => $this->id])->one();

            $dateOptions=DateOptions::find()
                ->where(['all_id'=>$this->id])
                ->andWhere(['<','created_at',$this->date])
                ->orderBy(['created_at'=>SORT_DESC])
                ->one();

            if (!empty($this->corrector)and isset($dateOptions)) {
                $this->contractHour=$dateOptions->contract_hour;
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    private function addError($arr=[])
    {
        if($arr!=[]){
            $this->errors=array_merge($this->errors,$arr);
            return true;
        }
        else{
            return false;
        }

    }

    public function dayReportIsValid()
    {
        $this->checkDayReport();

        if(empty($this->errors)){
            return true;
        }else{
            return false;
        }

    }


    public function checkDayReport($date=false)
    {
        if(!$date){
            if(empty($this->date)){
                $dt = new \DateTime(date("Y-m-d"));
                $dt->sub(new \DateInterval("P1D"));
            }else{
                $dt = new \DateTime($this->date);
            }


        }else{
            $dt = new \DateTime($date);
        }

        $this->getCorrector();

        if(empty($this->corrector->dateOptions)){
            $this->addError(['DateOptions'=>'DateOptions not found']);
        }


        $dateTime = DateTime::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        if(empty($dateTime)){
            $this->addError(['DateTime'=>'DateTime not found']);
        }

        $name = Name::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        if(empty($name)){
            $this->addError(['Name'=>'Name not found']);
        }

        $staticGeneral = StaticDataGeneral::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        if(empty($staticGeneral)){
            $this->addError(['staticGeneral'=>'staticGeneral not found']);
        }

        $staticHard = StaticDataHard::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();

        if(empty($staticHard)){
            $this->addError(['$staticHard'=>'$staticHard not found']);
        }

        $staticSensor = StaticDataSensor::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();

        if(empty($staticSensor)){
            $this->addError(['$staticSensor'=>'$staticSensor not found']);
        }

        $momentData = MomentData::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        if(empty($momentData)){
            $this->addError(['$momentData'=>'$momentData not found']);
        }


        $dt->add(new \DateInterval("PT".$this->contractHour."H"));
        $dn=clone $dt;
        $dn->add(new \DateInterval("P1D"));
        $hourData=HourData::find()
            ->where(['all_id'=>$this->id])
            ->andWhere(['>=','timestamp',$dt->format("Y-m-d H:i:s")])
            ->andWhere(['<','timestamp',$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_DESC])->all();


       // $hourData = $fTRG->dayHourReportGenerate($dt->format("Y"), $dt->format("m"), $dt->format("d"));
        if(empty($hourData)) {
            $this->addError(['HourData' => 'HourData not found']);
        }else{

            if (count($hourData) < 24) {
                $this->addError(['HourData' => 'HourData not full']);
            }
        }
           $fTRG = new FlouTechReportGenerator();
        $fTRG->counter_id = $this->id;
        $fTRG->contract_hour=$this->contractHour;

        $dayData = $fTRG->dayDayReportGenerate($dt->format("Y"), $dt->format("m"), $dt->format("d"));

        if(empty($dayData)){
            $this->addError(['$dayData'=>'$dayData not found']);
        }

    }

    public  function checkAllDayReports()
    {
        $correctors=CorrectorToCounter::find()->all();
        foreach($correctors as $corrector){
            $this->id=$corrector->id;
            echo "corrector #".$corrector->id." check begin \n";
            $firstHourData=HourData::find()
                ->where(['all_id'=>$corrector->id])
                ->orderBy(['timestamp'=>SORT_ASC])
                ->one();
            if(empty($firstHourData)){
                $firstDate=date("Y-m-d H:i:s");
            }else{
                $firstDate=$firstHourData->timestamp;
            }



            $dt=new \DateTime($firstDate);
            $dn=new \DateTime();

            for(;$dt<=$dn;){

                $report=PromReport::find()->where(['all_id'=>$corrector->id,'date'=>$dt->format("Y-m-d")])->one();
                if(empty($report)){ $report=new PromReport();}

                $report->report_type="day";
                $report->all_id=$corrector->id;
                $report->date=$dt->format("Y-m-d");
                $this->date=$dt->format("Y-m-d");
                if($this->dayReportIsValid()){
                    $report->is_valid=1;
                    echo "report for #".$dt->format("Y-m-d")." valid \n";
                }else{
                    $report->is_valid=0;
                    $report->errors=json_encode($this->errors);
                    echo "report for  #".$dt->format("Y-m-d")." not valid \n";
                }
                if(!$report->save()){print_r($report->getErrors());}
                $dt->add(new \DateInterval("P1D"));
                $this->errors=[];

            }

            echo "corrector #".$corrector->id." check ended \n";
        }

    }

    public  function checkThisDayReports()
    {
        $correctors=CorrectorToCounter::find()->all();
        foreach($correctors as $corrector){
            $this->id=$corrector->id;
            echo "corrector #".$corrector->id." check begin \n";

                $firstDate=date("Y-m-d");




            $dt=new \DateTime($firstDate);
            $dt->sub(new \DateInterval("P1D"));
            $dn=new \DateTime();

            for(;$dt<=$dn;){

                $report=PromReport::find()->where(['all_id'=>$corrector->id,'date'=>$dt->format("Y-m-d")])->one();
                if(empty($report)){ $report=new PromReport();}

                $report->report_type="day";
                $report->all_id=$corrector->id;
                $report->date=$dt->format("Y-m-d");
                $this->date=$dt->format("Y-m-d");
                if($this->dayReportIsValid()){
                    $report->is_valid=1;
                    echo "report for #".$dt->format("Y-m-d")." valid \n";
                }else{
                    $report->is_valid=0;
                    $report->errors=json_encode($this->errors);
                    echo "report for  #".$dt->format("Y-m-d")." not valid \n";
                }
                if(!$report->save()){print_r($report->getErrors());}
                $dt->add(new \DateInterval("P1D"));
                $this->errors=[];

            }

            echo "corrector #".$corrector->id." check ended \n";
        }

    }




}