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

class SummaryDayConsumpWidjet extends Widget
{

    public function run()
    {
        $dayConsumption=CorrectorComponent::AllCorrectorsDayConsumption();
        return $this->renderWidget(round($dayConsumption,2));
    }

    public function renderWidget($dayConsumption){
        echo'
        <div style="float:left; width: 25%; padding: 20px; min-height: 80px">

            <div style="width=100%;background-color:#c4f5c8;text-align: center;padding: 5px">
                <span style="font-family: \'Open Sans\';font-size: 24px ;color: white">Потребление за тек. сутки</span>
            </div>

            <div style="width=100%;background-color:#97c89a;text-align: center;padding: 5px;min-height: 100px">
               <p> <span style="font-family: \'Open Sans\';font-size: 40px;color: white">'.$dayConsumption.'</span><br>
                <span style="font-family: \'Open Sans\';font-size: 24px;color: white">куб./м.</span>
                </p>
            </div>

        </div>';

    }

}

