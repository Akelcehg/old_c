<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 16:10
 */

namespace app\modules\prom\components;


use app\models\CorrectorToCounter;
use app\models\DayData;
use app\models\MomentData;
use yii\base\Widget;

class SummaryMonthConsumpWidjet extends Widget
{

    public function run()
    {
        $monthConsumption=CorrectorComponent::AllCorrectorsMonthConsumption();
        return $this->renderWidget(round($monthConsumption,2));
    }

    public function renderWidget($consumpt){
        echo'    <div style="float:left;width: 25%;padding: 20px">

        <div style="width=100%;background-color:#a6dbfb;text-align: center;padding: 5px;min-height: 80px">
            <span style="font-family: \'Open Sans\';font-size: 24px ;color: white">Потребление за месяц</span>
        </div>

        <div style="width=100%;background-color:#6ac0f3;text-align: center;padding: 5px;min-height: 100px">
            <span style="font-family: \'Open Sans\';font-size: 40px;color: white">'.$consumpt.'</span><br>
            <span style="font-family: \'Open Sans\';font-size: 24px;color: white">куб./м.</span>
        </div>

    </div>
      ';

    }

}

