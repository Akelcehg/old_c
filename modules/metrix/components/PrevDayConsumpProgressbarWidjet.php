<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:32
 */

namespace app\modules\metrix\components;


use app\components\IfaceHelper;
use app\modules\counter\components\CounterComponent;
use Yii;
use yii\base\Widget;

class PrevDayConsumpProgressbarWidjet extends Widget
{
    public $user_type=false;

    public function run()
    {

        $prevDayC=MetrixComponent::AllCountersPrevDayConsumption($this->user_type);
        $prevDayC=round($prevDayC,0);
        $prevDayC2=$prevDayC*1.3;
        if($prevDayC2!=0 and $prevDayC!=0){
        $percentage=round($prevDayC/$prevDayC2*100,1);
        }else{
            $percentage=0;
        }
        echo IfaceHelper::ProgressBar(Yii::t('metrix','ConsumpPastDay'),$prevDayC,$prevDayC2.Yii::t('common','m3'),$percentage,'bg-color-greenLight');
    }

}