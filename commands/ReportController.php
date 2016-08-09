<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 16.02.16
 * Time: 16:16
 */

namespace app\commands;


use app\components\FlouTechCommandGenerator;
use app\components\FlouTechReportGenerator;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use app\models\Intervention;
use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use yii\console\Controller;


class ReportController extends Controller
{
    public function actionGeneratereports() {

        $dt=new \DateTime();
        $di=new \DateInterval("P1D");
        $di->invert=1;
        $dt->add($di);

        $this->GenerateDayReports($dt->format("y"),$dt->format("n"),$dt->format("j"));

    }
    public function actionGeneratebalance() {
        $this->GenerateBalanceReports();
    }

    public function actionThisdayhourdata() {

        $corrToCount=CorrectorToCounter::find()->all();
        $dt= new \DateTime();

        if($dt->format("H")<9){
            $dt->sub(new \DateInterval("P1D"));
        }



        foreach($corrToCount as $oneCorr) {
            $dn=new \DateTime($dt->format("Y-m-d")." 09:00:00");
            for($i=1;$i<24;$i++){

                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id=$oneCorr->id;
                $flouTechComGen->hourReportGenerate($dn->format("Y"),$dn->format("m"),$dn->format("d"),$dn->format("H"));

                $dn->add(new \DateInterval("PT1H"));
            }

        }

    }

    public function actionPrevdayhourdata() {

        $corrToCount=CorrectorToCounter::find()->all();
        $dt= new \DateTime();

        if($dt->format("H")<9){
            $dt->sub(new \DateInterval("P2D"));
        }else{
            $dt->sub(new \DateInterval("P1D"));
        }



        foreach($corrToCount as $oneCorr) {
            $dn=new \DateTime($dt->format("Y-m-d")." 09:00:00");
            for($i=1;$i<24;$i++){

                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id=$oneCorr->id;
                $flouTechComGen->hourReportGenerate($dn->format("Y"),$dn->format("m"),$dn->format("d"),$dn->format("H"));

                $dn->add(new \DateInterval("PT1H"));
            }

        }

    }

    public function GenerateDayReports($year,$month,$day) {

        $corrToCount=CorrectorToCounter::find()->all();

        foreach($corrToCount as $oneCorr) {
            $dateOptions=DateOptions::find()->where(['all_id'=>$oneCorr->id])->orderBy(['id'=>SORT_DESC])->one();
            if($dateOptions->contract_hour==date("H")){
                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id=$oneCorr->id;
                $flouTechComGen->contract_hour= $dateOptions->contract_hour;
                $flouTechComGen->DayFullReportGenerate($year,$month,$day);
            }
        }

    }


    public function GenerateBalanceReports() {

        $corrToCount=CorrectorToCounter::find()->all();


        foreach($corrToCount as $oneCorr) {
            $flouTechComGen = new FlouTechCommandGenerator();
            $flouTechComGen->counter_id=$oneCorr->id;
            $flouTechComGen->GetBalance();
        }
    }

    public function actionGenerateMonthReports( $year,$month) {


        $dt=new \DateTime($year."-".$month."-01");

        $this->GenerateDayReportsForMonth($dt->format("y"),$dt->format("n"));

    }//0b6831a499783774116100aacacd8545
    //0b6831a499783774116100aacacd8545

    public function actionGenerateMonthReportsDay( $year,$month ,$yearK,$monthK) {
        $corrToCount=CorrectorToCounter::find()->all();

        foreach($corrToCount as $oneCorr) {
            $flouTechComGen = new FlouTechCommandGenerator();
            $flouTechComGen->counter_id=$oneCorr->id;
            $flouTechComGen->MonthDayReportGenerate($year,$month,"01",$yearK,$monthK,"31");
        }
    }

    public function GenerateDayReportsForMonth($year,$month) {

        $corrToCount=CorrectorToCounter::find()->all();

        foreach($corrToCount as $oneCorr) {
            $flouTechComGen = new FlouTechCommandGenerator();
            $flouTechComGen->counter_id=$oneCorr->id;
            $flouTechComGen->MonthFullReportGenerate($year,$month);

        }
    }

    public function actionGenerateMonthReportsByDay( $year,$month,$n,$k) {


        $dt=new \DateTime($year."-".$month."-01");

        $this->GenerateDayReportsForMonthByDay($dt->format("y"),$dt->format("n"),$n,$k);

    }

    public function GenerateDayReportsForMonthByDay($year,$month,$n,$k) {

        $corrToCount=CorrectorToCounter::find()->all();

        foreach($corrToCount as $oneCorr) {
            $flouTechComGen = new FlouTechCommandGenerator();
            $flouTechComGen->counter_id=$oneCorr->id;
            $flouTechComGen->MonthFullReportGenerate($year,$month,$n,$k);

        }
    }

    public function GenerateDayReportsForMonthByDayAndId($id,$year,$month,$n,$k) {


            $flouTechComGen = new FlouTechCommandGenerator();
            $flouTechComGen->counter_id=$id;
            $flouTechComGen->MonthFullReportGenerate($year,$month,$n,$k);


    }

    public function actionGenerateThisIdThisDay( $id,$year,$month,$n,$k) {


        $dt=new \DateTime($year."-".$month."-01");

        $this->GenerateDayReportsForMonthByDayAndId($id,$dt->format("y"),$dt->format("n"),$n,$k);

    }


    public function GenerateDayReportsForMonth20($year,$month,$day,$yearK,$monthK,$dayK) {

        $corrToCount=CorrectorToCounter::find()->all();

        foreach($corrToCount as $oneCorr) {
            if($oneCorr->prog=="MConCore") {
                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id = $oneCorr->id;
                $flouTechComGen->MonthDayReportGenerate20($year,$month,$day,$yearK,$monthK,$dayK);
            }

        }
    }//0985268452

public function GenerateDayReportsForMonthOneCounter($id,$year,$month,$yearK,$monthK) {

    $corrToCount=CorrectorToCounter::find()->where(['id'=>$id])->one();


    $flouTechComGen = new FlouTechCommandGenerator();
    $flouTechComGen->counter_id= $corrToCount->id;
    $flouTechComGen->MonthDayReportGenerate($year,$month,"01",$yearK,$monthK,"31");


}//

    public function actionGen($id,$year,$month,$yearK,$monthK)
    {
        $this->GenerateDayReportsForMonthOneCounter($id,$year,$month,$yearK,$monthK);
    }

    public function actionGen20($year,$month,$day,$yearK,$monthK,$dayK)
    {
        $this->GenerateDayReportsForMonth20($year,$month,$day,$yearK,$monthK,$dayK);
    }


    public function actionCheckDayReports()
    {
        $reportCheck=new ReportCheckerComponent();
        $reportCheck->checkAllDayReports();
    }

    public function actionCheckThisDayReports()
    {
        $reportCheck=new ReportCheckerComponent();
        $reportCheck->checkThisDayReports();
    }


}