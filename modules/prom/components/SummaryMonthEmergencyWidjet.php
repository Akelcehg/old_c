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

class SummaryMonthEmergencyWidjet extends Widget
{

    public function run()
    {

        $emSit=0;
        $diag=0;
        $interv=0;

        $type = Yii::$app->request->get('type', "prom");
        $correctors=CorrectorToCounter::find()
            ->filterWhere(['type'=>$type])
            ->all();

        foreach($correctors as $corrector){

            $emSit+=$corrector->emergencySituationOnThisMonthCount;
            $diag+=$corrector->diagnosticOnThisMonthCount;
            $interv+=$corrector->interventionOnThisMonthCount;

        }



        return $this->renderWidget( $emSit, $diag,$interv);
    }

    public function renderWidget($emSit, $diag,$interv){
        echo'  <div style="float:left;width: 25%;padding: 20px;">

        <div style="width=100%;background-color:#f7c0a3;text-align: center;padding: 5px; min-height: 80px">
            <span style="font-family: \'Open Sans\';font-size: 24px ;color: white">АВАРИИ за тек. месяц</span>
        </div>

        <div style="width=100%;background-color:#e7a580;text-align: center;padding: 5px;min-height: 100px">
            <p style="font-family: \'Open Sans\';font-size: 18px;color: white;margin-bottom:0px"><a style="color: white;" class="prom-buttons" href="'.Yii::$app->urlManager->createUrl(['/prom/correctors/emergencylist']).'">Аварий: '.$emSit.'</a></p>
            <p style="font-family: \'Open Sans\';font-size: 18px;color: white;margin-bottom:0px"><a style="color: white;" class="prom-buttons" href="'.Yii::$app->urlManager->createUrl(['/prom/correctors/diagnosticlist']).'">Диагностика:'.$diag.'</a></p>
            <p style="font-family: \'Open Sans\';font-size: 18px;color: white;"><a style="color: white;" class="prom-buttons" href="'.Yii::$app->urlManager->createUrl(['/prom/correctors/interventionlist']).'">Вмешательства:'.$interv.'</a></p>
        </div>

    </div>
      ';

    }

}

