<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:32
 */

namespace app\modules\counter\components;


use app\components\IfaceHelper;
use app\modules\counter\components\CounterComponent;
use Yii;
use yii\base\Widget;

class DayConsumpProgressbarWidjet extends Widget
{
    public $user_type=false;

    public function run()
    {
        $dayC=CounterComponent::AllCountersDayConsumption($this->user_type);
        $prevDayC=CounterComponent::AllCountersPrevDayConsumption($this->user_type);
        $dayC=round($dayC,0);
        $prevDayC=round($prevDayC,0);
        if($dayC!=0 and $prevDayC!=0){
        $percentage=round($dayC/$prevDayC*100,1);
        }else{
            $percentage=0;
        }
        echo IfaceHelper::ProgressBar(Yii::t('counterWidgets','ConsumpCurrentDay'),$dayC,$prevDayC.Yii::t('common','m3'),$percentage,'bg-color-blueDark');
    }

}