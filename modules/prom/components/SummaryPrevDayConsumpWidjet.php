<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 16:10
 */

namespace app\modules\prom\components;


use app\models\CorrectorToCounter;
use app\models\MomentData;
use yii\base\Widget;

class SummaryPrevDayConsumpWidjet extends Widget
{

    public function run()
    {
        $dayConsumption=CorrectorComponent::AllCorrectorsPrevDayConsumption();
        return $this->renderWidget(round($dayConsumption,2));
    }

    public function renderWidget($dayConsumption){
        echo'
        <div style="float:left; width: 25%; padding: 20px">

            <div style="width=100%;background-color:#FFBDFD;text-align: center;padding: 5px;min-height: 80px">
                <span style="font-family: \'Open Sans\';font-size: 24px ;color: white">Потребление за пред. сутки</span>
            </div>

            <div style="width=100%;background-color:#FF68FF;text-align: center;padding: 5px;min-height: 100px">
                <span style="font-family: \'Open Sans\';font-size: 40px;color: white">'.$dayConsumption.'</span><br>
                <span style="font-family: \'Open Sans\';font-size: 24px;color: white">куб./м.</span>
            </div>

        </div>';

    }

}

