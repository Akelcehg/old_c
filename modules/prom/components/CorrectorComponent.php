<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:03
 */

namespace app\modules\prom\components;


use app\models\CorrectorToCounter;
use app\models\DayData;
use app\models\HourData;
use app\models\MomentData;
use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use Yii;
use yii\base\Component;

class CorrectorComponent extends Component
{

    public static function AllCorrectorsDayConsumption($type = false,$id= null)
    {
        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {
            if (isset($corrector->dateOptions)) {

                $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

                if ($corrector->dateOptions->contract_hour > date('H')) {
                    $date->sub(new \DateInterval("P1D"));
                }


                $momentData = MomentData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if (!empty($momentData)) {
                    $dayConsumption += $momentData->vday_sc;
                }
            }
        }
        return $dayConsumption;
    }

    public static function AllCorrectorsDayConsumptionWithoutLastHour($type = false)
    {
        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {

            $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

            if ($corrector->dateOptions->contract_hour > date('H')) {
                $date->sub(new \DateInterval("P1D"));
            }


            $dn = clone $date;
            $dn->sub(new \DateInterval('PT' . $date->format("i") . 'M' . $date->format("s") . 'S'));
            $datec = $dn->format("Y-m-d H:i:s");

            $momentData = MomentData::find()
                ->where(['all_id' => $corrector->id])
                ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                ->andWhere(['<', 'created_at', $datec])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if (!empty($momentData)) {
                $dayConsumption += $momentData->vday_sc;
            }
        }
        return $dayConsumption;
    }

    public static function AllCorrectorsPrevDayConsumption($type = false,$id=null)
    {
        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {
            if (isset($corrector->dateOptions)) {
                $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

                if ($corrector->dateOptions->contract_hour > date('H')) {
                    $date->sub(new \DateInterval("P1D"));
                }


                $momentData = MomentData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if (!empty($momentData)) {
                    $dayConsumption += $momentData->vprevday_sc;
                }
            }
        }
        return $dayConsumption;
    }

    public static function AllCorrectorsMonthConsumption($type = false,$id=null)
    {
        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }
        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;
        $dayData = 0;

        foreach ($correctors as $corrector) {
            $momentData = MomentData::find()
                ->where(['all_id' => $corrector->id])
                ->andWhere(['>', 'created_at', date('Y-m-d') . " 09:00:00"])
                ->orderBy(['id' => SORT_DESC])
                ->one();
            if (!empty($momentData)) {
                $dayConsumption += $momentData->vday_sc;
            }

            $dd = DayData::find()
                ->where(['>', 'timestamp', date('Y-m-1') . " 00:00:00"])
                ->andWhere(['all_id' => $corrector->id])
                ->all();//sum('v_sc');

            foreach ($dd as $do) {

                $dayData += $do->v_sc;
            }

        }


        return $dayData + $dayConsumption;

    }


    public static function GetCurrentMonthChart()
    {
        $result = [];
        for ($i = 1; $i < date('d'); $i++) {
            $dayData = DayData::find()->where(['year' => date("y"), "month" => date("n"), "day" => $i])->sum('v_sc');

            $result[] = [
                'label' => $i,
                'data' => [round($dayData, 3)]
            ];
        }

        $type = Yii::$app->request->get('type', "prom");

        $cc = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->all();
        $mdsum = 0;
        foreach ($cc as $c) {

            $md = MomentData::find()->where(['all_id' => $c->id])->orderBy(['created_at' => SORT_DESC])->one();
            $mdsum += $md->vday_sc;
        }

        $result[] = [
            'label' => date('d'),
            'data' => [round($mdsum, 3)]
        ];
        return $result;
    }


    public static function GetAverageTemp($type = false,$id=null)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {
            if (isset($corrector->dateOptions)) {

                $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

                if ($corrector->dateOptions->contract_hour > date('H')) {
                    $date->sub(new \DateInterval("P1D"));
                }


                $momentData = MomentData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if (!empty($momentData)) {
                    $dayConsumption += $momentData->tabs;
                }
            }
        }
        $dayConsumption = $dayConsumption / count($correctors);
        return round($dayConsumption,1);

    }

    public static function GetAveragePress($type = false,$id=null)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {
            if (isset($corrector->dateOptions)) {

                $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

                if ($corrector->dateOptions->contract_hour > date('H')) {
                    $date->sub(new \DateInterval("P1D"));
                }


                $momentData = MomentData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if (!empty($momentData)) {
                    $dayConsumption += $momentData->pabs;
                }
            }
        }
        $dayConsumption = $dayConsumption / count($correctors);
        return round($dayConsumption,1);

    }

    public static function GetAverageQ($type = false,$id=null)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();
        $dayConsumption = 0;

        foreach ($correctors as $corrector) {
            if (isset($corrector->dateOptions)) {

                $date = new \DateTime(date('Y-m-d') . " " . $corrector->dateOptions->contract_hour . ":00:00");

                if ($corrector->dateOptions->contract_hour > date('H')) {
                    $date->sub(new \DateInterval("P1D"));
                }


                $momentData = MomentData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['>', 'created_at', $date->format("Y-m-d H:i:s")])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                if (!empty($momentData)) {
                    $dayConsumption += $momentData->vconsum_sc;
                }
            }
        }
        $dayConsumption = $dayConsumption / count($correctors);
        return round($dayConsumption,1);

    }

    public static function GetLastAverageTemp($type = false,$id=null)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();

        $array = [];
        $date = new \DateTime();
        if( $date->format("H")<10){
            $date->sub(new \DateInterval('P2D'));
        }else{
            $date->sub(new \DateInterval('P1D'));
        }
        for ($i = 1; $i <= 7; $i++) {
            $dayConsumption = 0;
            foreach ($correctors as $corrector) {

                    $momentData = DayData::find()
                        ->where(['all_id' => $corrector->id])
                        ->andWhere(['day'=>$date->format("j"),'month'=>$date->format("n"),'year'=>$date->format("y")])
                        ->one();


                if (!empty($momentData)) {
                    $dayConsumption += $momentData->taverage;
                }
            }
            if(count($correctors)>0) {
                $array[] = $dayConsumption / count($correctors);
            }
            $date->sub(new \DateInterval('P1D'));
        }

        return array_reverse($array);

    }

    public static function GetLastAveragePress($type = false,$id=null)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();

        $array = [];
        $date = new \DateTime();
        if( $date->format("H")<10){
            $date->sub(new \DateInterval('P2D'));
        }else{
            $date->sub(new \DateInterval('P1D'));
        }
        for ($i = 1; $i <= 7; $i++) {
            $dayConsumption = 0;
            foreach ($correctors as $corrector) {

                $momentData = DayData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['day'=>$date->format("j"),'month'=>$date->format("n"),'year'=>$date->format("y")])
                    ->one();


                if (!empty($momentData)) {
                    $dayConsumption += $momentData->paverage;
                }
            }
            if(count($correctors)>0) {
                $array[] = $dayConsumption / count($correctors);
            }
            $date->sub(new \DateInterval('P1D'));
        }

        return array_reverse($array);

    }

    public static function GetLastAverageQ($type = false,$id=false)
    {

        if (!$type) {
            $type = Yii::$app->request->get('type', "prom");
        }

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $type])
            ->andFilterWhere(['id'=>$id])
            ->all();

        $array = [];
        $date = new \DateTime();
        if( $date->format("H")<10){
            $date->sub(new \DateInterval('P2D'));
        }else{
            $date->sub(new \DateInterval('P1D'));
        }

        for ($i = 1; $i <= 7; $i++) {
            $dayConsumption = 0;
            foreach ($correctors as $corrector) {

                $momentData = DayData::find()
                    ->where(['all_id' => $corrector->id])
                    ->andWhere(['day'=>$date->format("j"),'month'=>$date->format("n"),'year'=>$date->format("y")])
                    ->one();


                if (!empty($momentData)) {
                    $dayConsumption += $momentData->v_sc/24;
                }
            }
            if(count($correctors)>0) {
                $array[] = $dayConsumption / count($correctors);
            }
            $date->sub(new \DateInterval('P1D'));
        }

        return array_reverse($array);

    }

    public static function CorrectorsCount($type="prom"){
        $count=CorrectorToCounter::find()->where(['type'=>$type])->count();
        return $count;
    }

    public static function CorrectorsToOnline($type="prom"){
        $count=0;
        $correctors=CorrectorToCounter::find()->where(['type'=>$type])->all();
        $date=new \DateTime();
        $date->sub(new \DateInterval("P1D"));
        foreach($correctors as $corrector){

            $report=new ReportCheckerComponent();
            $report->id=$corrector->id;
            $report->date=$date->format("Y-m-d");
            if($report->dayReportIsValid())
            {
                $count++;
            }
        }



        return $count;
    }
    public  static  function CorrectorsOnline($type="prom"){
        echo self::CorrectorsToOnline($type)."/".self::CorrectorsCount($type);
    }


}