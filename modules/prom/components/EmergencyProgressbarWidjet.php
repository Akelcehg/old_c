<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 22:05
 */

namespace app\modules\prom\components;


use yii\base\Widget;

class EmergencyProgressbarWidjet extends Widget
{

    public function run()
    {

        $prevDayC=CorrectorComponent::AllCorrectorsMonthConsumption();
        $prevDayC=round($prevDayC,0);
        $prevDayC2=$prevDayC*1.3;
        if($prevDayC2!=0 and $prevDayC!=0){
            $percentage=round($prevDayC/$prevDayC2*100,1);
        }else{
            $percentage=0;
        }
        echo IfaceHelper::ProgressBar('Потребление за месяц',$prevDayC,$prevDayC2,$percentage,'bg-color-blueLight');
    }

}