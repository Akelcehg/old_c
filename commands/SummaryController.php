<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.06.16
 * Time: 12:08
 */

namespace app\commands;


use app\models\SummaryReport;
use yii\console\Controller;

class SummaryController extends Controller
{

    public function actionGen()
    {
        $this->AutoSummary();
    }


    public function AutoSummary()
    {
        $summary = new SummaryReport();
        $summary->grs = round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("grs"), 2);
        $summary->prom = round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"), 2);
        $summary->legal_entity = round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"), 2);
        $summary->house_metering = round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"), 2);
        $summary->individual = round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption("individual"), 2);
        $summary->all =
            round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"), 2) +
            round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"), 2) +
            round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"), 2) +
            round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption("individual"), 2);
        if ($summary->save()) {
            return true;
        } else {
            return false;
        }
    }



}