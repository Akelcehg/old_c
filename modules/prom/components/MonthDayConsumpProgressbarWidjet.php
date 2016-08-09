<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:32
 */

namespace app\modules\prom\components;


use app\components\IfaceHelper;
use app\modules\counter\components\CounterComponent;
use app\modules\prom\components\Limit\Limits;
use Yii;
use yii\base\Widget;

class MonthDayConsumpProgressbarWidjet extends Widget
{
    public $type=false;
    public $id=null;


    public function run()
    {
        if($this->type=='all'){
            $this->type='prom';
            $prevDayC=CorrectorComponent::AllCorrectorsMonthConsumption($this->type,$this->id)+CounterComponent::AllCountersMonthConsumption()+
                CounterComponent::AllCountersMonthConsumption('house_metering');
            $prevDayC2=$prevDayC*1.3;
        }else{
            $prevDayC=CorrectorComponent::AllCorrectorsMonthConsumption($this->type,$this->id);


            if($this->id) {

                $limit=new Limits();
                $limit->all_id=$this->id;
                $limit->year=date('Y');
                $limit->month=date('m');

                if ($limit->GetLimit()) {
                    $limitCount = $limit->GetLimit()->limit;
                } else {
                    $limitCount = 0;
                }
            }else{
                $limitCount=$prevDayC*1.3;
            }

            $prevDayC2= $limitCount;
        }

        $prevDayC=round($prevDayC,0);
        $prevDayC2=round($prevDayC2,0);
        if($prevDayC2!=0 and $prevDayC!=0){
        $percentage=round($prevDayC/$prevDayC2*100,1);
        }else{
            $percentage=0;
        }
        //Consumption for the month Потребление за месяц

        echo IfaceHelper::ProgressBar(Yii::t('promWidgets','Consumption for the month'),$prevDayC,$prevDayC2.'м3',$percentage,'bg-color-blueLight');
    }

}