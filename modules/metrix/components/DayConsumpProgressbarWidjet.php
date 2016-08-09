<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:32
 */

namespace app\modules\metrix\components;


use app\components\IfaceHelper;

use Yii;
use yii\base\Widget;

class DayConsumpProgressbarWidjet extends Widget
{
    public $user_type=false;

    public function run()
    {
        $dayC=MetrixComponent::AllCountersDayConsumption($this->user_type);
        $prevDayC=MetrixComponent::AllCountersPrevDayConsumption($this->user_type);
        $dayC=round($dayC,0);
        $prevDayC=round($prevDayC,0);
        if($dayC!=0 and $prevDayC!=0){
        $percentage=round($dayC/$prevDayC*100,1);
        }else{
            $percentage=0;
        }
        echo IfaceHelper::ProgressBar(Yii::t('metrix','ConsumpCurrentDay'),$dayC,$prevDayC.Yii::t('common','m3'),$percentage,'bg-color-blueDark');
    }

}