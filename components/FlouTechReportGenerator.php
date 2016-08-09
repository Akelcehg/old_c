<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 13.02.2016
 * Time: 23:39
 */
namespace app\components;

use app\models\CommandConveyor;
use app\models\CorrectorToCounter;
use app\models\DayData;
use app\models\FloutechCommands;
use app\models\HourData;

class FlouTechReportGenerator extends \yii\base\Component
{

    public $counter_id;
    public $contract_hour =9;
  //  public $command;



    public function monthReportGenerate($year,$month){

        $dayDataArray=[];

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-01");
        $dn= new \DateTime($year."-".$month."-01");

        //$dt->add(new \DateInterval('PT'.$this->contract_hour.'H'));



        for($h=1;$dn->format("m")== $dt->format("m");$h++) {

            $dayData=DayData::find()->where(['all_id'=>$correctorInfo->id])
                ->andWhere(['like','timestamp',$dt->format("Y-m-d")])
                ->orderBy(['id'=>SORT_DESC])->one();
            // exit(var_dump(['all_id'=>$this->counter_id,'year'=>$dt->format("y"),'month'=>$dt->format("n"),'day'=>$dt->format("j"),'hour_n'=>$dt->format("G")]));

            if( $dayData)
            {
                $dayDataArray[]= $dayData;
            }
            else
            {
                /* $flouTechGen= new FlouTechCommandGenerator();
                 $flouTechGen->counter_id=$this->counter_id;
                 $flouTechGen->oneDayDataReportGenerate($dt->format("y"),$dt->format("m"),$dt->format("d"));*/
            }

            $dt->add(new \DateInterval('P1D'));

        }

        return  $dayDataArray;

    }





    public function dayHourReportGenerate($year,$month,$day){

        $hourDataArray=[];

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);

        $dt->add(new \DateInterval('PT'.$this->contract_hour.'H'));


        for($h=0;$h<24;$h++) {

            $hourData=HourData::find()
                ->where(['all_id'=>$this->counter_id,'timestamp'=>$dt->format("Y-m-d H:i:s")])
                ->orderBy(['id'=>SORT_DESC])->one();

           // exit(var_dump(['all_id'=>$this->counter_id,'year'=>$dt->format("y"),'month'=>$dt->format("n"),'day'=>$dt->format("j"),'hour_n'=>$dt->format("G")]));

            if($hourData)
            {
                $hourDataArray[]=$hourData;
            }
            else
            {
                /*$flouTechGen= new FlouTechCommandGenerator();
                $flouTechGen->counter_id=$this->counter_id;
                $flouTechGen->hourReportGenerate($dt->format("y"),$dt->format("n"),$dt->format("j"),$dt->format("G"));*/
            }

            //echo $dt->format("Y-m-d H:i:s");

            $dt->add(new \DateInterval('PT1H'));

        }

        return $hourDataArray;

    }

    public function dayDayReportGenerate($year,$month,$day){



        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);

        //$dt->add(new \DateInterval('PT'.$this->contract_hour.'H'));

        $daydata=DayData::find()->where(['all_id'=>$correctorInfo->id])
            ->andWhere(['like','timestamp',$dt->format("Y-m-d")])
            ->orderBy(['id'=>SORT_DESC])->one();



          /* if(!$daydata)
            {
                $flouTechGen= new FlouTechCommandGenerator();
                $flouTechGen->contract_hour=$this->contract_hour;
                $flouTechGen->counter_id=$this->counter_id;
                $flouTechGen->oneDayDataReportGenerate($dt->format("y"),$dt->format("n"),$dt->format("j"));

            }*/



        return  $daydata;
    }



}