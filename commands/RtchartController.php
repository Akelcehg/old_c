<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 16.02.16
 * Time: 16:16
 */

namespace app\commands;


use app\components\FlouTechCommandGenerator;
use app\components\FlouTechReportGenerator;
use app\models\CorrectorToCounter;
use app\models\Intervention;
use app\models\ModemStatus;
use yii\console\Controller;


class RtchartController extends Controller
{

    public function actionSetCommand()
    {
       /* $correctors=CorrectorToCounter::find();
        foreach( $correctors->all() as $corrector){

            if(!$corrector->isForcedMomentData()) {
                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id = $corrector->id;
                $flouTechComGen->MomentDataCommand([1=>$corrector->branch_id]);
            }

        }

        return true;*/
    }

    public function actionSetHourCommand()
    {
        $correctors=CorrectorToCounter::find();
        foreach( $correctors->all() as $corrector){

            if(!$corrector->isForcedHourData()) {
                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id = $corrector->id;
                $flouTechComGen->everyHourReportGenerate();
                $flouTechComGen->SpecCommand();
            }

        }

        return true;
    }
}