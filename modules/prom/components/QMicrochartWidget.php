<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\models\Intervention;
use Yii;
use yii\base\Widget;

class QMicrochartWidget extends Widget
{
    public $type=false;
    public $id=null;


public function run()
{
    $q=CorrectorComponent::GetAverageQ($this->type,$this->id);

    if($this->type="grs"){ $percentage=($q-0.25)/6500*100;}else{
        $percentage=($q-0.25)/65*100;
    }


    $lastAverage=CorrectorComponent::GetLastAverageQ($this->type,$this->id);



    $this->renderMC($q,$percentage,$lastAverage);
}


public function renderMC($q,$percentage,$lastAverage){

?><div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <?=\app\components\IfaceHelper::EasyPieChart(ceil($percentage),50,50,'txt-color-greenLight','m3 font-xs',Yii::t('promWidgets','Volume consumption'),$q)?>
            <?=\app\components\IfaceHelper::MinMax(0.25,65,'bg-color-darken txt-color-white','bg-color-blueDark txt-color-white')?>
            <?=\app\components\IfaceHelper::Sparklines('bar',implode(',',$lastAverage),'70px','33px',"sparkline txt-color-darken hidden-sm hidden-md pull-right")?>

</div><?php
}

}