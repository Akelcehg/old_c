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

class DayConsumpProgressbarWidjet extends Widget
{
    public $type=false;
    public $id=false;

    public function run()
    {
        if($this->type=='all'){
            $this->type='prom';
            $dayC=CorrectorComponent::AllCorrectorsDayConsumption($this->type,$this->id)+
                CounterComponent::AllCountersDayConsumption('house_metering')+
                CounterComponent::AllCountersDayConsumption();
            $prevDayC=CorrectorComponent::AllCorrectorsPrevDayConsumption($this->type,$this->id)+CounterComponent::AllCountersPrevDayConsumption()+
                CounterComponent::AllCountersDayConsumption('house_metering');
        }else{
            $dayC=CorrectorComponent::AllCorrectorsDayConsumption('prom',$this->id);



            if($this->id) {

                $limit=new Limits();
                $limit->all_id=$this->id;
                $limit->year=date('Y');
                $limit->month=date('m');

                if ($limit->GetLimit()) {
                    $limitCount = $limit->GetLimit()->limit / date('t');
                } else {
                    $limitCount = 0;
                }
            }else{
                $limitCount=CorrectorComponent::AllCorrectorsPrevDayConsumption('prom',$this->id);
            }

            $prevDayC=$limitCount;//CorrectorComponent::AllCorrectorsPrevDayConsumption('prom',$this->id);
        }

        $dayC=round($dayC,0);
        $prevDayC=round($prevDayC,0);
        if($dayC!=0 and $prevDayC!=0){
        $percentage=round($dayC/$prevDayC*100,1);
        }else{
            $percentage=0;
        }
        //Consumption for the current day Потребление за тек. сутки

        echo IfaceHelper::ProgressBar(Yii::t('promWidgets','Consumption for the current day'),$dayC,$prevDayC.'м3',$percentage,'bg-color-blueDark');
    }

}