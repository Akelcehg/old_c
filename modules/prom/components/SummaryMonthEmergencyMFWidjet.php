<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 16:10
 */

namespace app\modules\prom\components;


use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\Intervention;
use Yii;
use yii\base\Widget;

class SummaryMonthEmergencyMFWidjet extends Widget
{
    private $type;
    public $id = null;

    public function run()
    {

        $emSit = 0;
        $diag = 0;
        $interv = 0;
        $this->type = Yii::$app->request->get('type', "prom");

        $correctors = CorrectorToCounter::find()
            ->filterWhere(['type' => $this->type])
            ->andFilterWhere(['id' => $this->id])
            ->all();

        foreach ($correctors as $corrector) {

            $emSit += $corrector->emergencySituationOnThisMonthCount;
            $diag += $corrector->diagnosticOnThisMonthCount;
            $interv += $corrector->interventionOnThisMonthCount;

        }


        return $this->renderWidget($emSit, $diag, $interv);
    }

    public function renderWidget($emSit, $diag, $interv)
    {
        echo '
    <div style="float:left;width: 100%;">
        <div style="width=100%;text-align: center;">
            <span style="font-family: \'Open Sans\';font-size: 12px ;color: black">' . Yii::t('promWidgets', 'Accidents in the past month') . '</span>
        </div>
        <div style="width=100%;text-align: center;padding: 5px;padding-left: 25px">

            <p style="font-family: \'Open Sans\';font-size: 10px;float:left;color: black;margin-right:20px;  margin-bottom:0px">
                <a style="color: black;" class="prom-buttons" href="' . Yii::$app->urlManager->createUrl(['/prom/correctors/emergencylist', 'type' => $this->type]) . '">' . Yii::t('promWidgets', 'Accidents') . ':' . $emSit . '</a>
            </p>

            <p style="font-family: \'Open Sans\';font-size: 10px;float:left;color: black;margin-right:20px; margin-bottom:0px">
                <a style="color: black;" class="prom-buttons" href="' . Yii::$app->urlManager->createUrl(['/prom/correctors/diagnosticlist', 'type' => $this->type]) . '">' . Yii::t('promWidgets', 'Diagnostic Comms') . ':' . $diag . '</a>
            </p>

            <p style="font-family: \'Open Sans\';font-size: 10px;float:left;color: black;">
                <a style="color: black;" class="prom-buttons" href="' . Yii::$app->urlManager->createUrl(['/prom/correctors/interventionlist', 'type' => $this->type]) . '">' . Yii::t('promWidgets', 'Interventions') . ':' . $interv . '</a>
            </p>

        </div>

    </div>
      ';

    }

}

